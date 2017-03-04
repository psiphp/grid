<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use Psi\Component\Grid\Action\DeleteAction;
use Psi\Component\Grid\Column\BooleanColumn;
use Psi\Component\Grid\Column\DateTimeColumn;
use Psi\Component\Grid\Column\MoneyColumn;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\Column\SelectColumn;
use Psi\Component\Grid\Column\TextColumn;
use Psi\Component\Grid\Filter\BooleanFilter;
use Psi\Component\Grid\Filter\ChoiceFilter;
use Psi\Component\Grid\Filter\DateFilter;
use Psi\Component\Grid\Filter\NumberFilter;
use Psi\Component\Grid\Filter\StringFilter;
use Psi\Component\Grid\Form\GridExtension;
use Psi\Component\Grid\Metadata\Driver\AnnotationDriver;
use Psi\Component\Grid\Metadata\Driver\ArrayDriver;
use Psi\Component\ObjectAgent\AgentFinder;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;
use Psi\Component\Grid\GridMetadataFactory;
use Psi\Component\Grid\EventDispatchingGridMetadataFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class GridFactoryBuilder
{
    private $agentFinder;
    private $eventDispatcher;
    private $formFactoryBuilder;

    private $actions = [];
    private $columns = [];
    private $filters = [];
    private $metadataDrivers = [];

    public static function create(
        AgentFinder $agentFinder,
        FormFactoryBuilderInterface $formFactoryBuilder = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $instance = new self();
        $instance->agentFinder = $agentFinder;
        $instance->formFactoryBuilder = $formFactoryBuilder ?: Forms::createFormFactoryBuilder();
        $instance->eventDispatcher = $eventDispatcher ?: new EventDispatcher();

        return $instance;
    }

    public static function createWithDefaults(AgentFinder $agentFinder, FormFactoryBuilderInterface $formFactoryBuilder = null)
    {
        return self::create($agentFinder, $formFactoryBuilder)
            ->addAction(new DeleteAction(), 'delete')

            ->addColumn(new PropertyColumn(), 'property')
            ->addColumn(new SelectColumn($agentFinder), 'select')
            ->addColumn(new MoneyColumn(), 'money')
            ->addColumn(new BooleanColumn(), 'boolean')
            ->addColumn(new DateTimeColumn(), 'datetime')
            ->addColumn(new TextColumn(), 'text')

            ->addFilter(new StringFilter(), 'string')
            ->addFilter(new BooleanFilter(), 'boolean')
            ->addFilter(new NumberFilter(), 'number')
            ->addFilter(new DateFilter(), 'date')
            ->addFilter(new ChoiceFilter(), 'choice');
    }

    public function addArrayDriver(array $mapping)
    {
        $this->metadataDrivers[] = new ArrayDriver($mapping);

        return $this;
    }

    public function addAnnotationDriver()
    {
        $this->metadataDrivers[] = new AnnotationDriver();

        return $this;
    }

    public function addAction(ActionInterface $action, $alias = null): self
    {
        $this->actions[$alias] = $action;

        return $this;
    }

    public function addFilter(FilterInterface $filter, $alias = null)
    {
        $this->filters[$alias] = $filter;

        return $this;
    }

    public function addColumn(ColumnInterface $column, $alias = null)
    {
        $this->columns[$alias] = $column;

        return $this;
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function createGridFactory()
    {
        if (empty($this->metadataDrivers)) {
            throw new \InvalidArgumentException(
                'You must add at least one metadata driver (e.g. ->addArrayDriver, ->addAnnotationDriver, ->addXmlDriver)'
            );
        }

        $actionRegistry = new ActionRegistry();
        foreach ($this->actions as $alias => $action) {
            $actionRegistry->register($action, $alias);
        }
        $columnRegistry = new ColumnRegistry();
        foreach ($this->columns as $alias => $column) {
            $columnRegistry->register($column, $alias);
        }
        $filterRegistry = new FilterRegistry();
        foreach ($this->filters as $alias => $filter) {
            $filterRegistry->register($filter, $alias);
        }

        $metadataDriver = new DriverChain($this->metadataDrivers);
        $columnFactory = new ColumnFactory($columnRegistry);
        $metadataFactory = new MetadataFactory($metadataDriver);
        $gridMetadataFactory = new EventDispatchingGridMetadataFactory(
            new GridMetadataFactory($metadataFactory),
            $this->eventDispatcher
        );

        $validator = Validation::createValidator();

        $formFactory = $this->formFactoryBuilder
            ->addExtension(new ValidatorExtension($validator))
            ->addExtension(new GridExtension(
                $columnRegistry,
                $filterRegistry
            ))
            ->getFormFactory();

        $filterFactory = new FilterBarFactory($formFactory, $filterRegistry);
        $queryFactory = new QueryFactory($metadataFactory);

        $gridViewFactory = new GridViewFactory(
            $columnFactory,
            $filterFactory,
            $queryFactory
        );

        $actionPerformer = new ActionPerformer($actionRegistry);

        return new GridFactory(
            $this->agentFinder,
            $gridMetadataFactory,
            $gridViewFactory,
            $actionPerformer
        );
    }
}
