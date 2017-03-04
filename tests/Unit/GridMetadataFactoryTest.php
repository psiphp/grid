<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Metadata\MetadataFactory;
use Psi\Component\Grid\GridMetadataFactory;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Tests\Util\MetadataUtil;

class GridMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var GridMetadataFactory
     */
    private $gridMetadataFactory;

    public function setUp()
    {
        $this->metadataFactory = $this->prophesize(MetadataFactory::class);

        $this->gridMetadataFactory = new GridMetadataFactory(
            $this->metadataFactory->reveal()
        );

        $this->metadata = MetadataUtil::createClass(\stdClass::class, [
            'default' => MetadataUtil::createGrid('default', []),
        ]);
    }

    public function testGetGridMetadata()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(
            $this->metadata
        );

        $metadata = $this->gridMetadataFactory->getGridMetadata(\stdClass::class, 'default');
        $this->assertInstanceOf(GridMetadata::class, $metadata);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No grid variants
     */
    public function testNoVariants()
    {
        $metadata = MetadataUtil::createClass(\stdClass::class, []);
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(
            $metadata
        );

        $this->gridMetadataFactory->getGridMetadata(\stdClass::class, 'default');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown grid variant
     */
    public function testUnknownGridVariant()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(
            $this->metadata
        );

        $this->gridMetadataFactory->getGridMetadata(\stdClass::class, 'foobar');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not locate grid metadata
     */
    public function testNoMetadata()
    {
        $this->metadataFactory->getMetadataForClass(\stdClass::class)->willReturn(null);

        $this->gridMetadataFactory->getGridMetadata(\stdClass::class, 'foobar');
    }
}
