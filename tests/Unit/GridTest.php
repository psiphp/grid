<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\FilterBar;
use Psi\Component\Grid\Grid;
use Psi\Component\Grid\Paginator;
use Psi\Component\Grid\Table;

class GridTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $table = $this->prophesize(Table::class);
        $paginator = $this->prophesize(Paginator::class);
        $filter = $this->prophesize(FilterBar::class);

        $grid = new Grid(
            \stdClass::class,
            'foobar',
            $table->reveal(),
            $paginator->reveal(),
            $filter->reveal()
        );

        $this->assertEquals(\stdClass::class, $grid->getClassFqn());
        $this->assertEquals('foobar', $grid->getName());
        $this->assertSame($table->reveal(), $grid->getTable());
        $this->assertSame($filter->reveal(), $grid->getFilter());
        $this->assertSame($paginator->reveal(), $grid->getPaginator());
    }
}
