<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\Event\GridMetadataEvent;
use Psi\Component\Grid\EventDispatchingGridMetadataFactory;
use Psi\Component\Grid\Events;
use Psi\Component\Grid\GridMetadataFactoryInterface;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatchingGridMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $dispatcher;
    private $innerFactory;
    private $factory;
    private $metadata;

    public function setUp()
    {
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->innerFactory = $this->prophesize(GridMetadataFactoryInterface::class);

        $this->factory = new EventDispatchingGridMetadataFactory(
            $this->innerFactory->reveal(),
            $this->dispatcher->reveal()
        );

        $this->metadata = MetadataUtil::createGrid('main', []);
    }

    /**
     * It should dispatch events.
     */
    public function testDispatchEvents()
    {
        $this->innerFactory->getGridMetadata(\stdClass::class, 'main')->willReturn($this->metadata);
        $this->dispatcher->dispatch(Events::METADATA_LOADED, new GridMetadataEvent($this->metadata))->shouldBeCalled();
        $this->factory->getGridMetadata(\stdClass::class, 'main');
    }
}
