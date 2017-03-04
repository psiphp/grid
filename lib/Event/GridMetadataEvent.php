<?php

namespace Psi\Component\Grid\Event;

use Symfony\Component\EventDispatcher\Event;
use Psi\Component\Grid\Metadata\GridMetadata;

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
