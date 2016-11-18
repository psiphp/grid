<?php

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\AgentInterface;
use Psi\Component\ObjectAgent\Query\Query;

class GridViewFactory
{
    private $cellFactory;
    private $filterFactory;

    public function __construct(
        ColumnFactory $cellFactory,
        FilterBarFactory $filterFactory
    ) {
        $this->cellFactory = $cellFactory;
        $this->filterFactory = $filterFactory;
    }

    public function createView(AgentInterface $agent, GridContext $gridContext, GridMetadata $gridMetadata)
    {
        // create the filter form based on the metadata and submit any data.
        $filterForm = $this->filterFactory->createForm($gridMetadata, $agent->getCapabilities());
        $filterForm->submit($gridContext->getFilter());

        // create the query and get the data collection from the object-agent.
        $query = Query::create(
            $gridContext->getClassFqn(),
            $this->filterFactory->createExpression($gridMetadata, $filterForm->getData()),
            $gridContext->getOrderings(),
            $gridContext->getPageOffset(),
            $gridContext->getPageSize()
        );
        $collection = new \IteratorIterator($agent->query($query));

        return new View\Grid(
            $gridContext->getClassFqn(),
            $gridMetadata->getName(),
            new View\Table($this->cellFactory, $gridMetadata, $gridContext, $collection, $gridContext),
            new View\Paginator($gridContext, count($collection), $this->getNumberOfRecords($agent, $query)),
            new View\FilterBar($filterForm->createView(), $gridContext),
            new View\ActionBar($gridMetadata)
        );
    }

    private function getNumberOfRecords(AgentInterface $agent, Query $query)
    {
        if (false === $agent->getCapabilities()->canQueryCount()) {
            return;
        }

        return $agent->queryCount($query);
    }
}
