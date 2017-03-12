<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Psi\Component\Grid\Metadata\ActionMetadata;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Tests\Model\Article;

abstract class DriverTestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getDriver(): DriverInterface;

    public function testReturnNullNoMetadata()
    {
        $reflection = new \ReflectionClass(\stdClass::class);
        $metadata = $this->getDriver()->loadMetadataForClass($reflection);

        $this->assertNull($metadata);
    }

    public function testLoadMetadata()
    {
        $reflection = new \ReflectionClass(Article::class);
        $metadata = $this->getDriver()->loadMetadataForClass($reflection);

        $this->assertInstanceOf(ClassMetadata::class, $metadata);
        $grids = $metadata->getGrids();
        $this->assertCount(2, $grids);
        $this->assertArrayHasKey('foobar', $grids);
        $grid = $grids['foobar'];
        $this->assertInstanceOf(GridMetadata::class, $grid);
        $this->assertEquals('foobar', $grid->getName());
        $this->assertEquals(50, $grid->getPageSize());
        $columns = $grid->getColumns();
        $this->assertCount(2, $columns);
        $this->assertArrayHasKey('title', $columns);
        $column = $columns['title'];
        $this->assertInstanceOf(ColumnMetadata::class, $column);
        $this->assertEquals('title', $column->getName());
        $this->assertEquals('property_value', $column->getType());
        $this->assertEquals(['property' => 'title'], $column->getOptions());
        $this->assertEquals(['tag1'], $column->getTags());

        $actions = $grid->getActions();
        $this->assertCount(1, $actions);
        $action = reset($actions);
        $this->assertInstanceOf(ActionMetadata::class, $action);
        $this->assertEquals('delete_selected', $action->getName());
        $this->assertEquals('delete', $action->getType());
        $this->assertEquals(['tag1'], $action->getTags());
    }
}
