<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Row;
use Psi\Component\Grid\Tests\Model\Article;
use Psi\Component\Grid\Tests\Util\MetadataUtil;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->viewFactory = $this->prophesize(CellFactory::class);
        $this->gridMetadata = MetadataUtil::createGrid('test', [
            'columns' => [
                'bar' => [],
                'boo' => [],
            ],
        ]);

        $this->data = new Article('test', 10);

        $this->row = new Row(
            $this->viewFactory->reveal(),
            $this->gridMetadata,
            $this->data
        );
    }

    /**
     * It should return the data.
     */
    public function testAccessData()
    {
        $this->assertSame($this->data, $this->row->getData());
    }
}
