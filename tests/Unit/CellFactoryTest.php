<?php

namespace Psi\Component\Grid\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\CellRegistry;
use Psi\Component\Grid\CellViewInterface;
use Psi\Component\Grid\RowData;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CellFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $registry;

    public function setUp()
    {
        $this->registry = $this->prophesize(CellRegistry::class);

        $this->factory = new CellFactory($this->registry->reveal());

        $this->cell = $this->prophesize(CellInterface::class);
        $this->view = $this->prophesize(CellViewInterface::class);
    }

    /**
     * It should produce cells.
     */
    public function testFactory()
    {
        $typeName = 'foobar';
        $rowData = RowData::fromObject(new \stdClass());
        $options = [];
        $this->registry->get($typeName)->willReturn($this->cell->reveal());
        $this->cell->configureOptions(Argument::type(OptionsResolver::class))->shouldBeCalled();
        $this->cell->createView($rowData, [
            'column_name' => 'column_one',
        ])->willReturn($this->view->reveal());

        $view = $this->factory->create(
            'column_one',
            $typeName,
            $rowData,
            $options
        );
    }
}
