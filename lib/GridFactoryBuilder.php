<?php

namespace Psi\Component\Grid;

use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use Psi\Component\Grid\Action\DeleteAction;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\Column\SelectColumn;
use Psi\Component\Grid\Filter\BooleanFilter;
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

final class GridFactoryBuilder
{
    private $agentFinder;
    private $formFactoryBuilder;

    private $actions = [];
    private $columns = [];
    private $filters = [];
    private $metadataDrivers = [];

    public static function create(
        AgentFinder $agentFinder,
        FormFactoryBuilderInterface $formFactoryBuilder = null
    ) {
        $instance = new self();
        $instance->agentFinder = $agentFinder;
        $instance->formFactoryBuilder = $formFactoryBuilder ?: Forms::createFormFactoryBuilder();

        return $instance;
    }

    public static function createWithDefaults(AgentFinder $agentFinder, FormFactoryBuilderInterface $formFactoryBuilder = null)
    {
        return self::create($agentFinder, $formFactoryBuilder)
            ->addAction('delete', new DeleteAction())
            ->addColumn('property', new PropertyColumn())
            ->addColumn('select', new SelectColumn($agentFinder))
            ->addFilter('string', new StringFilter())
            ->addFilter('boolean', new BooleanFilter())
            ->addFilter('number', new NumberFilter());
    }

    public function addArrayDriver(array $mapping)
    {
        $this->metadataDrivers[] = new ArrayDriver($mapping);

        return $this;
    }

    public function addAnnotationDriver()
    {
        $this->metadataDrivers[] = new AnnotationDriver();
    }

    public function addAction(string $name, ActionInterface $action): self
    {
        $this->actions[$name] = $action;

        return $this;
    }

    public function addFilter(string $name, FilterInterface $filter)
    {
        $this->filters[$name] = $filter;

        return $this;
    }

    public function addColumn(string $name, ColumnInterface $column)
    {
        $this->columns[$name] = $column;

        return $this;
    }

    public function createGridFactory()
    {
        if (empty($this->metadataDrivers)) {
            throw new \InvalidArgumentException(
                'You must add at least one metadata driver (e.g. ->addArrayDriver, ->addAnnotationDriver, ->addXmlDriver)'
            );
        }

        $actionRegistry = new ActionRegistry();
        foreach ($this->actions as $name => $action) {
            $actionRegistry->register($name, $action);
        }
        $columnRegistry = new ColumnRegistry();
        foreach ($this->columns as $name => $column) {
            $columnRegistry->register($name, $column);
        }
        $filterRegistry = new FilterRegistry();
        foreach ($this->filters as $name => $filter) {
            $filterRegistry->register($name, $filter);
        }

        $metadataDriver = new DriverChain($this->metadataDrivers);
        $columnFactory = new ColumnFactory($columnRegistry);
        $metadataFactory = new MetadataFactory($metadataDriver);

        $validator = Validation::createValidator();

        $formFactory = $this->formFactoryBuilder
            ->addExtension(new ValidatorExtension($validator))
            ->addExtension(new GridExtension(
                $columnRegistry,
                $filterRegistry
            ))
            ->getFormFactory();

        $filterFactory = new FilterBarFactory($formFactory, $filterRegistry);

        $gridViewFactory = new GridViewFactory(
            $columnFactory,
            $filterFactory
        );

        $actionPerformer = new ActionPerformer($actionRegistry);

        return new GridFactory(
            $this->agentFinder,
            $metadataFactory,
            $gridViewFactory,
            $actionPerformer
        );
    }
}
