<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\ObjectAgent\AgentFinder;

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
        GridMetadataFactoryInterface $metadataFactory,
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
        $gridMetadata = $this->metadataFactory->getGridMetadata($real, $context->getVariant());

        return new Grid(
            $this->gridViewFactory,
            $this->actionPerformer,
            $agent,
            $context,
            $gridMetadata
        );
    }
}
