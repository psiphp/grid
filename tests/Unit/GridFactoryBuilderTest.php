<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\ActionInterface;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\Grid\GridFactoryBuilder;
use Psi\Component\ObjectAgent\AgentFinder;

class GridFactoryBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $agentFinder;

    public function setUp()
    {
        $this->agentFinder = $this->prophesize(AgentFinder::class);
        $this->column = $this->prophesize(ColumnInterface::class);
        $this->action = $this->prophesize(ActionInterface::class);
        $this->filter = $this->prophesize(FilterInterface::class);
    }

    /**
     * It should throw an exception if no metadata drivers were registered.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must add at least one metadata driver (e.g. ->addArrayDriver, ->addAnnotationDriver, ->addXmlDriver)
     */
    public function testNoMetadataDrivers()
    {
        GridFactoryBuilder::createWithDefaults($this->agentFinder->reveal())
            ->createGridFactory();
    }

    /**
     * It should create a new grid factory.
     */
    public function testBuilder()
    {
        $factory = GridFactoryBuilder::createWithDefaults($this->agentFinder->reveal())
            ->addArrayDriver([])
            ->addColumn($this->column->reveal())
            ->addFilter($this->filter->reveal())
            ->addAction($this->action->reveal())
            ->createGridFactory();
    }
}
