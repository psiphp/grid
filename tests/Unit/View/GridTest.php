<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\View\ActionBar;
use Psi\Component\Grid\View\FilterBar;
use Psi\Component\Grid\View\Grid;
use Psi\Component\Grid\View\Paginator;
use Psi\Component\Grid\View\Table;

class GridTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $table = $this->prophesize(Table::class);
        $paginator = $this->prophesize(Paginator::class);
        $filterBar = $this->prophesize(FilterBar::class);
        $actionBar = $this->prophesize(ActionBar::class);

        $grid = new Grid(
            \stdClass::class,
            'foobar',
            $table->reveal(),
            $paginator->reveal(),
            $filterBar->reveal(),
            $actionBar->reveal()
        );

        $this->assertEquals(\stdClass::class, $grid->getClassFqn());
        $this->assertEquals('foobar', $grid->getName());
        $this->assertSame($table->reveal(), $grid->getTable());
        $this->assertSame($filterBar->reveal(), $grid->getFilter());
        $this->assertSame($paginator->reveal(), $grid->getPaginator());
    }
}
