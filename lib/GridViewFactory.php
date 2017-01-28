<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\View\Grid as GridView;
use Psi\Component\ObjectAgent\AgentInterface;
use Psi\Component\ObjectAgent\Query\Composite;
use Psi\Component\ObjectAgent\Query\Query;

class GridViewFactory
{
    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * @var FilterFactory
     */
    private $filterFactory;

    public function __construct(
        ColumnFactory $columnFactory,
        FilterBarFactory $filterFactory,
        QueryFactory $queryFactory
    ) {
        $this->columnFactory = $columnFactory;
        $this->filterFactory = $filterFactory;
        $this->queryFactory = $queryFactory;
    }

    public function createView(AgentInterface $agent, GridContext $gridContext, GridMetadata $gridMetadata): GridView
    {
        // create the filter form based on the metadata and submit any data.
        $filterForm = $this->filterFactory->createForm($gridMetadata, $agent->getCapabilities());

        if ($gridContext->getFilter()) {
            $filterForm->submit($gridContext->getFilter());

            if (false === $filterForm->isValid()) {
                foreach ($filterForm->getErrors(true) as $name => $error) {
                    $message[] = sprintf(
                        '%s %s',
                        $error->getOrigin()->getPropertyPath(),
                        $error->getMessage()
                    );
                }

                throw new \InvalidArgumentException(sprintf(
                    'Invalid filter form: ' . implode(', ', $message)
                ));
            }
        }

        $criteria = [
            'criteria' => $this->filterFactory->createExpression($gridMetadata, $filterForm->getData()),
            'orderings' => $this->resolveOrderings($gridContext->getOrderings(), $gridMetadata),
            'firstResult' => $gridContext->getPageOffset(),
            'maxResults' => $gridContext->isPaginated() ? $gridContext->getPageSize() : null,
        ];

        if ($gridMetadata->hasQuery()) {
            $query = $this->queryFactory->createQuery($agent->getRealClassFqn($gridContext->getClassFqn()), $gridMetadata->getQuery());
            $criteria['selects'] = $query->getSelects();
            $criteria['joins'] = $query->getJoins();

            if ($query->hasExpression()) {
                if (null === $criteria['criteria']) {
                    $criteria['criteria'] = $query->getExpression();
                } else {
                    // filter and user criterias need to be combined
                    $criteria['criteria'] = new Composite(Composite::AND, [$query->getExpression(), $criteria['criteria']]);
                }
            }
        }

        // create the query and get the data collection from the object-agent.
        $query = Query::create($gridContext->getClassFqn(), $criteria);
        $collection = new \IteratorIterator($agent->query($query));

        return new View\Grid(
            $gridContext->getClassFqn(),
            $gridMetadata->getName(),
            new View\Table($this->columnFactory, $gridMetadata, $gridContext, $collection, $gridContext),
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

    private function resolveOrderings(array $orderings, GridMetadata $metadata)
    {
        $columns = $metadata->getColumns();
        $realOrderings = [];

        foreach ($orderings as $columnName => $direction) {
            if (!isset($columns[$columnName])) {
                throw new \RuntimeException(sprintf(
                    'Invalid column "%s"', $columnName
                ));
            }

            $column = $columns[$columnName];
            $options = $column->getOptions();

            if (!isset($options['sort_field'])) {
                $options['sort_field'] = $columnName;
            }

            $realOrderings[$options['sort_field']] = $direction;
        }

        return $realOrderings;
    }
}
