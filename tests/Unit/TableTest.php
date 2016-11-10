<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Table;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\View\ViewFactory;

class TableTest extends \PHPUnit_Framework_TestCase
{
    private $table;
    private $gridMetadata;
    private $viewFactory;

    public function setUp()
    {
        $this->gridMetadata = MetadataUtil::createGrid('foobar', [
            'columns' => [
                'foobar' => ['type' => 'hello'],
                'barfoo' => ['type' => 'hello'],
            ],
        ]);

        $this->viewFactory = $this->prophesize(ViewFactory::class);
    }

    /**
     * It should return headers.
     */
    public function testHeaders()
    {
        $table = $this->create(
            new \ArrayObject([
                new \stdClass(),
            ]),
            [
                'foobar' => 'asc',
            ]
        );

        $headers = $table->getHeaders();
        $this->assertCount(2, $headers);

        $this->assertArrayHasKey('foobar', $headers);
        $this->assertArrayHasKey('barfoo', $headers);

        $header = $headers['foobar'];
        $this->assertTrue($header->isSorted());
        $this->assertTrue($header->isSortAscending());

        $header = $headers['barfoo'];
        $this->assertFalse($header->isSorted());
        $this->assertFalse($header->isSortAscending());
    }

    public function create(\Traversable $collection, array $orderings)
    {
        return new Table(
            $this->viewFactory->reveal(),
            $this->gridMetadata,
            $collection,
            $orderings
        );
    }
}
