<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Cell;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\RowData;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumnTest extends \PHPUnit_Framework_TestCase
{
    private $cell;

    public function setUp()
    {
        $this->accessor = $this->prophesize(PropertyAccessorInterface::class);
        $this->cell = new PropertyColumn($this->accessor->reveal());
    }

    /**
     * It should create a view from some data.
     */
    public function testCreateView()
    {
        $object = new \stdClass();
        $this->accessor->getValue($object, 'foobar')->willReturn('barfoo');
        $view = $this->createCell($object, []);

        $this->assertInstanceOf(CellInterface::class, $view);
        $this->assertEquals('barfoo', $view->getValue());
    }

    /**
     * It should return the view named in the options.
     */
    public function testViewView()
    {
        $object = new \stdClass();
        $this->accessor->getValue($object, 'foobar')->willReturn('barfoo');
        $view = $this->createCell($object, [
            'view' => 'foobar',
        ]);

        $this->assertEquals('foobar', $view->getView());
    }

    private function createCell($object, $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('column_name', 'foobar');
        $this->cell->configureOptions($resolver);

        $options = $resolver->resolve($options);

        return $this->cell->createCell(
            new RowData($object),
            $options
        );
    }
}
