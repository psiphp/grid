<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\Grid\GridMetadataFactory;

class GridFactory
{
    /**
     * @var AgentFinder
     */
    private $agentFinder;

    /**
     * @var GridMetadataFactory
     */
    private $metadataFactory;

    /**
     * @var ActionPerformer
     */
    private $actionPerformer;

    /**
     * @var GridViewFactory
     */
    private $gridViewFactory;

    public function __construct(
        AgentFinder $agentFinder,
        GridMetadataFactory $metadataFactory,
        GridViewFactory $gridViewFactory,
        ActionPerformer $actionPerformer
    ) {
        $this->agentFinder = $agentFinder;
        $this->metadataFactory = $metadataFactory;
        $this->gridViewFactory = $gridViewFactory;
        $this->actionPerformer = $actionPerformer;
    }

    public function createGrid(string $classFqn, array $context = []): Grid
    {
        $context = new GridContext($classFqn, $context);

        return $this->doLoadGrid($context);
    }

    private function doLoadGrid(GridContext $context): Grid
    {
        // find the agent and get the grid metadata.
        $agent = $this->agentFinder->findFor($context->getClassFqn());

        $real = $agent->getCanonicalClassFqn($context->getClassFqn());
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($real)) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        $gridMetadata = $this->metadataFactory->getGridMetadata($context->getClassFqn(), $context->getVariant());

        return new Grid(
            $this->gridViewFactory,
            $this->actionPerformer,
            $agent,
            $context,
            $gridMetadata
        );
    }
}
