<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\ColumnRegistry;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ColumnRegistry
     */
    private $registry;

    /**
     * @var ColumnFactory
     */
    private $factory;

    /**
     * @var ColumnInterface
     */
    private $column;

    public function setUp()
    {
        $this->registry = $this->prophesize(ColumnRegistry::class);
        $this->factory = new ColumnFactory($this->registry->reveal());

        $this->column = $this->prophesize(ColumnInterface::class);
    }

    /**
     * It should produce columns.
     */
    public function testFactory()
    {
        $typeName = 'foobar';
        $rowData = new \stdClass();
        $options = [];
        $this->registry->get($typeName)->willReturn($this->column->reveal());
        $this->column->configureOptions(Argument::type(OptionsResolver::class))->shouldBeCalled();
        $this->column->getCellTemplate()->willReturn('foo_template');
        $this->column->buildCell(
            Argument::type(Cell::class),
            [
                'column_name' => 'column_one',
                'sort_field' => null,
                'label' => 'column_one',
                'cell_template' => 'foo_template',
            ]
        )->shouldBeCalled();
        $this->column->getParent()->willReturn(null);

        $cell = $this->factory->createCell(
            'column_one',
            $typeName,
            $rowData,
            $options
        );
    }

    /**
     * It should throw an exception if a circular reference is detected.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Circular reference detected on class
     */
    public function testCircularReference()
    {
        $this->registry->get('foobar')->willReturn($this->column->reveal());
        $this->column->getParent()->willReturn('foobar');

        $cell = $this->factory->createCell(
            'column_one',
            'foobar',
            new \stdClass(),
            []
        );
    }
}
