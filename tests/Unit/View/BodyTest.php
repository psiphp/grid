<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\Body;
use Psi\Component\Grid\View\Row;

class BodyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cellFactory = $this->prophesize(CellFactory::class);
    }

    public function testBody()
    {
        $body = $this->create(new \ArrayIterator([
            $one = new \stdClass(),
            $two = new \stdClass(),
        ]));

        $row = $body->current();
        $this->assertInstanceOf(Row::class, $row);
    }

    private function create(\Traversable $collection)
    {
        $gridMetadata = MetadataUtil::createGrid('test', []);

        return new Body($this->cellFactory->reveal(), $gridMetadata, $collection);
    }
}
