<?php

namespace Psi\Component\Grid;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Event\GridMetadataEvent;
use Psi\Component\Grid\Events;

class EventDispatchingGridMetadataFactory implements GridMetadataFactoryInterface
{
    /**
     * @var GridMetadataFactoryInterface
     */
    private $innerFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        GridMetadataFactoryInterface $innerFactory,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->innerFactory = $innerFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getGridMetadata(string $classFqn, string $variant): GridMetadata
    {
        $gridMetadata = $this->innerFactory->getGridMetadata($classFqn, $variant);
        $event = new GridMetadataEvent($gridMetadata);
        $this->eventDispatcher->dispatch(Events::METADATA_LOADED, $event);

        return $event->getMetadata();
    }
}
