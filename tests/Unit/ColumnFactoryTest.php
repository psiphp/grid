<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\ColumnRegistry;
use Psi\Component\Grid\RowData;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $registry;

    public function setUp()
    {
        $this->registry = $this->prophesize(ColumnRegistry::class);

        $this->factory = new ColumnFactory($this->registry->reveal());

        $this->cell = $this->prophesize(ColumnInterface::class);
        $this->view = $this->prophesize(CellInterface::class);
    }

    /**
     * It should produce cells.
     */
    public function testFactory()
    {
        $typeName = 'foobar';
        $rowData = new RowData(new \stdClass());
        $options = [];
        $this->registry->get($typeName)->willReturn($this->cell->reveal());
        $this->cell->configureOptions(Argument::type(OptionsResolver::class))->shouldBeCalled();
        $this->cell->createCell($rowData, [
            'column_name' => 'column_one',
        ])->willReturn($this->view->reveal());

        $view = $this->factory->createCell(
            'column_one',
            $typeName,
            $rowData,
            $options
        );
    }
}
