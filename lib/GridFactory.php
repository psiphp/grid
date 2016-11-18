<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\AgentFinder;

class GridFactory
{
    private $agentFinder;
    private $metadataFactory;
    private $actionPerformer;
    private $gridViewFactory;

    public function __construct(
        AgentFinder $agentFinder,
        MetadataFactory $metadataFactory,
        GridViewFactory $gridViewFactory,
        ActionPerformer $actionPerformer
    ) {
        $this->agentFinder = $agentFinder;
        $this->metadataFactory = $metadataFactory;
        $this->gridViewFactory = $gridViewFactory;
        $this->actionPerformer = $actionPerformer;
    }

    public function createGrid(string $classFqn, array $context): Grid
    {
        $context = new GridContext($classFqn, $context);

        try {
            return $this->doLoadGrid($context);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load grid for class "%s"', $classFqn
            ), 0, $exception);
        }
    }

    private function doLoadGrid(GridContext $context): Grid
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($context->getClassFqn())) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        // find the agent and get the grid metadata.
        $agent = $this->agentFinder->findFor($context->getClassFqn());
        $gridMetadata = $this->resolveGridMetadata($metadata->getGrids(), $context->getVariant());

        return new Grid(
            $this->gridViewFactory,
            $this->actionPerformer,
            $agent,
            $context,
            $gridMetadata
        );
    }

    private function resolveGridMetadata(array $grids, string $variant = null)
    {
        if (empty($grids)) {
            throw new \InvalidArgumentException('No grid variants are available');
        }

        // if no explicit grid variant is requested, return the first one that
        // was defined.
        if (null === $variant) {
            return reset($grids);
        }

        if (!isset($grids[$variant])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown grid variant "%s", available variants: "%s"',
                implode('", "', array_keys($grids))
            ));
        }

        return $grids[$variant];
    }
}
