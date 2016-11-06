<?php

namespace Psi\Component\Grid;

use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\View\ViewFactory;
use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\Query\Query;

class GridFactory
{
    private $agentFinder;
    private $metadataFactory;
    private $viewFactory;

    public function __construct(
        AgentFinder $agentFinder,
        MetadataFactory $metadataFactory,
        ViewFactory $viewFactory
    )
    {
        $this->agentFinder = $agentFinder;
        $this->metadataFactory = $metadataFactory;
        $this->viewFactory = $viewFactory;
    }

    public function loadGrid(\ReflectionClass $class, string $name = null): Grid
    {
        try {
            return $this->doLoadGrid($class, $name);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load grid for class "%s"', $class->getName()
            ), null, $e);
        }
    }

    private function doLoadGrid(\ReflectionClass $class, string $name = null): Grid
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($class->getName())) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        $gridMetadata = $this->resolveGridMetadata($metadata->getGrids(), $name);

        $agent = $this->agentFinder->findAgentFor($class->getName());
        $query = Query::create($class->getName());
        $data = $agent->query($query);

        return new Grid($this->viewFactory, $gridMetadata, $data);
    }

    private function resolveGridMetadata(array $grids, string $name = null)
    {
        if (empty($grids)) {
            throw new \InvalidArgumentException('No grids are available');
        }

        if (null === $name) {
            return reset($grids);
        }

        if (!isset($grids[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown grid "%s", available grids: "%s"',
                implode('", "', array_keys($grids))
            ));
        }

        return $grids[$name];
    }
}
