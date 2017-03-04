<?php

namespace Psi\Component\Grid\Tests\Unit\Metadata;

use Psi\Component\Grid\Tests\Util\MetadataUtil;

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
