<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Metadata;

use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\Grid;

class GridMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should filter columns.
     */
    public function testFilterColumns()
    {
        $metadata = MetadataUtil::createGrid('default', [
            'columns' => [
                'one' => [],
                'two' => [],
                'three' => [],
            ],
        ]);

        $metadata = $metadata->filterColumns(function ($column) {
            return $column->getName() !== 'one';
        });

        $this->assertCount(2, $metadata->getColumns());
    }

    public function testColumnForGroups()
    {
        $metadata = MetadataUtil::createGrid('default', [
            'columns' => [
                'one' => [
                    'groups' => ['foobar', 'baz'],
                ],
                'two' => [],
                'three' => [
                    'groups' => [ Grid::DEFAULT_GROUP, 'foobar' ],
                ],
            ],
        ]);

        $columns = $metadata->getColumnsForGroups([ 'main' ]);
        $this->assertCount(2, $columns);

        $columns = $metadata->getColumnsForGroups([ 'foobar' ]);
        $this->assertCount(2, $columns);

        $columns = $metadata->getColumnsForGroups([ 'baz' ]);
        $this->assertCount(1, $columns);
    }

    /**
     * It should filter filters.
     */
    public function testFilterFilters()
    {
        $metadata = MetadataUtil::createGrid('default', [
            'filters' => [
                'one' => [],
                'two' => [],
                'three' => [],
            ],
        ]);

        $metadata = $metadata->filterFilters(function ($filter) {
            return $filter->getName() !== 'one';
        });

        $this->assertCount(2, $metadata->getFilters());
    }

    /**
     * It should filter actions.
     */
    public function testFilterActions()
    {
        $metadata = MetadataUtil::createGrid('default', [
            'actions' => [
                'one' => [],
                'two' => [],
                'three' => [],
            ],
        ]);

        $metadata = $metadata->filterActions(function ($action) {
            return $action->getName() !== 'one';
        });

        $this->assertCount(2, $metadata->getActions());
    }
}
