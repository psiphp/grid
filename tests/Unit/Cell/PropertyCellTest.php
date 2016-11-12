<?php

namespace Psi\Component\Grid\Tests\Unit\Cell;

use Psi\Component\Grid\Cell\PropertyCell;
use Psi\Component\Grid\CellViewInterface;
use Psi\Component\Grid\RowData;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyCellTest extends \PHPUnit_Framework_TestCase
{
    private $cell;

    public function setUp()
    {
        $this->accessor = $this->prophesize(PropertyAccessorInterface::class);
        $this->cell = new PropertyCell($this->accessor->reveal());
    }

    /**
     * It should create a view from some data.
     */
    public function testCreateView()
    {
        $object = new \stdClass();

        $options = new OptionsResolver();
        $options->setDefault('column_name', 'foobar');
        $this->cell->configureOptions($options);
        $this->accessor->getValue($object, 'foobar')->willReturn('barfoo');

        $options = $options->resolve([]);
        $view = $this->cell->createView(
            RowData::fromObject($object),
            $options
        );

        $this->assertInstanceOf(CellViewInterface::class, $view);
        $this->assertEquals('barfoo', $view->getValue());
    }
}
