<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\View;

use Prophecy\Argument;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Tests\Model\Article;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\Cell;
use Psi\Component\Grid\View\Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->columnFactory = $this->prophesize(ColumnFactory::class);
        $this->gridMetadata = MetadataUtil::createGrid('test', [
            'columns' => [
                'bar' => [
                    'type' => 'barbar',
                ],
                'boo' => [],
            ],
        ]);

        $this->data = new Article('test', 10);
        $gridContext = new GridContext('test', []);

        $this->row = new Row(
            $this->columnFactory->reveal(),
            $this->gridMetadata,
            $gridContext,
            $this->data
        );
        $this->cellView = $this->prophesize(Cell::class);
    }

    public function testIterate()
    {
        $this->columnFactory->createCell(
            'bar',
            'barbar',
            Argument::type(Article::class),
            []
        )->shouldBeCalled()->willReturn(
            $this->cellView->reveal()
        );
        $view = $this->row->current();
        $this->assertSame($this->cellView->reveal(), $view);
    }

    /**
     * It should return the data.
     */
    public function testAccessData()
    {
        $this->assertSame($this->data, $this->row->getData());
    }
}
