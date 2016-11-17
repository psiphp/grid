<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\Body;
use Psi\Component\Grid\View\Table;

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

        $this->viewFactory = $this->prophesize(ColumnFactory::class);
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

        $headers = $table->getHeaders();
        $this->assertCount(2, $headers);

        $this->assertArrayHasKey('foobar', $headers);
        $this->assertArrayHasKey('barfoo', $headers);

        $header = $headers['foobar'];
        $this->assertTrue($header->isSorted());
        $this->assertTrue($header->isSortAscending());

        $header = $headers['barfoo'];
        $this->assertFalse($header->isSorted());
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
        return new Table(
            $this->viewFactory->reveal(),
            $this->gridMetadata,
            $collection,
            new GridContext(\stdClass::class, [
                'orderings' => $orderings,
            ])
        );
    }
}
