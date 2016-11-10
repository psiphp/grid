<?php

namespace Psi\Component\Grid\Tests\Functional\Metadata\Driver;

use Psi\Component\Grid\Tests\Functional\GridTestCase;
use Psi\Component\Grid\Tests\Functional\Metadata\Driver\Model\Product;

class AnnotationDriverTest extends GridTestCase
{
    /**
     * It should load metadata from class annotations.
     */
    public function testLoadMetadata()
    {
        $driver = $this->createContainer([])->get('metadata.annotation_driver');
        $reflection = new \ReflectionClass(Product::class);
        $metadata = $driver->loadMetadataForClass($reflection);

        $this->assertCount(2, $metadata->getGrids());
        $this->assertArrayHasKey('main', $metadata->getGrids());
        $grid = $metadata->getGrids()['main'];

        $this->assertCount(2, $grid->getColumns());
        $this->assertArrayHasKey('title', $grid->getColumns());
        $column = $grid->getColumns()['title'];
        $this->assertEquals('property', $column->getType());
        $this->assertEquals(['property' => 'name'], $column->getOptions());

        $this->assertArrayHasKey('price', $grid->getColumns());
        $column = $grid->getColumns()['price'];
        $this->assertEquals('property', $column->getType());

        $this->assertCount(2, $grid->getFilters());
        $this->assertArrayHasKey('title', $grid->getFilters());
        $column = $grid->getFilters()['title'];
        $this->assertEquals('string', $column->getType());
        $this->assertEquals(['foo' => 'bar'], $column->getOptions());

        $this->assertArrayHasKey('price', $grid->getFilters());
        $column = $grid->getFilters()['price'];
        $this->assertEquals('cost', $column->getField());
        $this->assertEquals('number', $column->getType());

        $this->assertEquals(10, $grid->getPageSize());
    }
}
