<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Event;

use Psi\Component\Grid\Metadata\GridMetadata;
use Symfony\Component\EventDispatcher\Event;

class GridMetadataEvent extends Event
{
    private $metadata;

    public function __construct(GridMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    public function getMetadata(): GridMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(GridMetadata $metadata)
    {
        $this->metadata = $metadata;
    }
}
