<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Event\GridMetadataEvent;
use Psi\Component\Grid\Metadata\GridMetadata;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
    ) {
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
