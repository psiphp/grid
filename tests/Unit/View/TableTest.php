<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Prophecy\Argument;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\Body;
use Psi\Component\Grid\View\Header;
use Psi\Component\Grid\View\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    private $table;
    private $gridMetadata;
    private $columnFactory;

    public function setUp()
    {
        $this->gridMetadata = MetadataUtil::createGrid('foobar', [
            'columns' => [
                'foobar' => ['type' => 'hello'],
                'barfoo' => ['type' => 'hello'],
            ],
        ]);

        $this->columnFactory = $this->prophesize(ColumnFactory::class);
        $this->header1 = $this->prophesize(Header::class);
        $this->header2 = $this->prophesize(Header::class);
    }

    /**
     * It should return headers.
     */
    public function testHeaders()
    {
        $table = $this->create(
            new \ArrayIterator([
                new \stdClass(),
            ]),
            [
                'foobar' => 'asc',
            ]
        );
        $this->columnFactory->createHeader(
            Argument::type(GridContext::class),
            'foobar',
            'hello',
            []
        )->willReturn($this->header1->reveal());
        $this->columnFactory->createHeader(
            Argument::type(GridContext::class),
            'barfoo',
            'hello',
            []
        )->willReturn($this->header2->reveal());

        $headers = $table->getHeaders();
        $this->assertCount(2, $headers);

        $this->assertArrayHasKey('foobar', $headers);
        $this->assertArrayHasKey('barfoo', $headers);

        $this->assertContainsOnlyInstancesOf(Header::class, $headers);
    }

    /**
     * It should return the body.
     */
    public function testReturnBody()
    {
        $table = $this->create(
            new \ArrayIterator([
                new \stdClass(),
            ]),
            [
                'foobar' => 'asc',
            ]
        );

        $body = $table->getBody();
        $this->assertInstanceOf(Body::class, $body);
    }

    public function create(\Traversable $collection, array $orderings)
    {
        $gridContext = new GridContext('test', []);

        return new Table(
            $this->columnFactory->reveal(),
            $this->gridMetadata,
            $gridContext,
            $collection,
            new GridContext(\stdClass::class, [
                'orderings' => $orderings,
            ])
        );
    }
}
