<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Metadata\MetadataFactory;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\QueryMetadata;
use Psi\Component\Grid\QueryFactory;
use Psi\Component\ObjectAgent\Query\Converter\ArrayConverter;
use Psi\Component\ObjectAgent\Query\Query;

class QueryFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $queryFactory;
    private $converter;

    private $query;
    private $metadata;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);
        $this->converter = $this->prophesize(ArrayConverter::class);

        $this->queryFactory = new QueryFactory(
            $this->metadataFactory->reveal(),
            $this->converter->reveal()
        );

        $this->query = Query::create(\stdClass::class);
        $this->queryMetadata = new QueryMetadata('bar');
        $this->metadata = new ClassMetadata(\stdClass::class, [], ['foo' => $this->queryMetadata]);
    }

    /**
     * It should throw an exception if it can not locate grid metadata.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not locate
     */
    public function testNoGridMetadata()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(null);
        $this->queryFactory->createQuery(\stdClass::class, 'foobar');
    }

    /**
     * It should thow an exception if the query does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Query "foobar" for "stdClass" is not known. Known queries: "foo"
     */
    public function testQueryNotExist()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn($this->metadata);
        $this->queryFactory->createQuery(\stdClass::class, 'foobar');
    }

    /**
     * It should return an object-agent query.
     */
    public function testCreateQuery()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn($this->metadata);
        $this->converter->__invoke([
            'from' => \stdClass::class,
            'selects' => [],
            'joins' => [],
            'criteria' => [],
        ])->shouldBeCalled()->willReturn($this->query);

        $this->queryFactory->createQuery(\stdClass::class, 'foo');
    }
}
