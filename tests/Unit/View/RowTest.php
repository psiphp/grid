<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Prophecy\Argument;
use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\CellViewInterface;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\RowData;
use Psi\Component\Grid\Tests\Model\Article;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cellFactory = $this->prophesize(CellFactory::class);
        $this->gridMetadata = MetadataUtil::createGrid('test', [
            'columns' => [
                'bar' => [
                    'type' => 'barbar',
                ],
                'boo' => [],
            ],
        ]);

        $this->data = new Article('test', 10);

        $this->row = new Row(
            $this->cellFactory->reveal(),
            $this->gridMetadata,
            $this->data
        );
        $this->cellView = $this->prophesize(CellViewInterface::class);
    }

    public function testIterate()
    {
        $this->cellFactory->create(
            'bar',
            'barbar',
            Argument::type(RowData::class),
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
