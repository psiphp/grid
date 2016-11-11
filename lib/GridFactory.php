<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\ObjectAgent\AgentInterface;
use Psi\Component\ObjectAgent\Query\Query;

class GridFactory
{
    private $agentFinder;
    private $metadataFactory;
    private $cellFactory;
    private $filterFactory;

    public function __construct(
        AgentFinder $agentFinder,
        MetadataFactory $metadataFactory,
        CellFactory $cellFactory,
        FilterFormFactory $filterFactory
    ) {
        $this->agentFinder = $agentFinder;
        $this->metadataFactory = $metadataFactory;
        $this->cellFactory = $cellFactory;
        $this->filterFactory = $filterFactory;
    }

    public function loadGrid(string $classFqn, array $options): Grid
    {
        $options = new GridContext($classFqn, $options);

        try {
            return $this->doLoadGrid($classFqn, $options);
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(sprintf(
                'Could not load grid for class "%s"', $classFqn
            ), 0, $exception);
        }
    }

    private function doLoadGrid(string $classFqn, GridContext $options): Grid
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($classFqn)) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        $gridMetadata = $this->resolveGridMetadata($metadata->getGrids(), $options->getVariant());
        $agent = $this->agentFinder->findFor($classFqn);

        $form = $this->filterFactory->createForm($gridMetadata, $agent->getCapabilities());
        $form->submit($options->getFilter());
        $expression = $this->getExpression($gridMetadata, $form->getData());

        $query = Query::create(
            $classFqn,
            $expression,
            $options->getOrderings(),
            $options->getPageOffset(),
            $options->getPageSize()
        );
        $collection = $agent->query($query);

        return new Grid(
            $classFqn,
            $gridMetadata->getName(),
            new Table($this->cellFactory, $gridMetadata, $collection, $options),
            new Paginator($options, count($collection), $this->getNumberOfRecords($agent, $query)),
            new FilterForm($form->createView(), $options)
        );
    }

    private function getExpression(GridMetadata $gridMetadata, array $filterData)
    {
        return $this->filterFactory->createExpression($gridMetadata, $filterData);
    }

    private function getNumberOfRecords(AgentInterface $agent, Query $query)
    {
        if (false === $agent->getCapabilities()->canQueryCount()) {
            return;
        }

        return $agent->queryCount($query);
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
