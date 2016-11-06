<?php

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\ObjectAgent\Query\Query;
use Psi\Component\View\ViewFactory;

class GridFactory
{
    private $agentFinder;
    private $metadataFactory;
    private $viewFactory;

    public function __construct(
        AgentFinder $agentFinder,
        MetadataFactory $metadataFactory,
        ViewFactory $viewFactory
    ) {
        $this->agentFinder = $agentFinder;
        $this->metadataFactory = $metadataFactory;
        $this->viewFactory = $viewFactory;
    }

    public function loadGrid(string $classFqn, array $options): Grid
    {
        $options = new GridOptions($options);

        try {
            return $this->doLoadGrid($classFqn, $options);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load grid for class "%s"', $classFqn
            ), null, $exception);
        }
    }

    private function doLoadGrid(string $classFqn, GridOptions $options): Grid
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($classFqn)) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        $gridMetadata = $this->resolveGridMetadata($metadata->getGrids(), $options->getVariant());

        $agent = $this->agentFinder->findAgentFor($classFqn);
        $query = Query::create(
            $classFqn,
            null,
            $options->getOrderings(),
            $options->getPageOffset(),
            $options->getPageSize()
        );
        $collection = $agent->query($query);

        return new Grid($this->viewFactory, $gridMetadata, $collection);
    }

    private function resolveGridMetadata(array $grids, string $variant = null)
    {
        if (empty($grids)) {
            throw new \InvalidArgumentException('No grid variants are available');
        }

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
