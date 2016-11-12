<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\Metadata\GridMetadata;

class Body implements \Iterator
{
    private $cellFactory;
    private $gridMetadata;
    private $collection;

    public function __construct(
        CellFactory $cellFactory,
        GridMetadata $gridMetadata,
        \Iterator $collection
    ) {
        $this->cellFactory = $cellFactory;
        $this->gridMetadata = $gridMetadata;
        $this->collection = $collection;
    }

    public function current()
    {
        return new Row(
            $this->cellFactory,
            $this->gridMetadata,
            $this->collection->current()
        );
    }

    public function next()
    {
        return $this->collection->next();
    }

    public function key()
    {
        return $this->collection->key();
    }

    public function rewind()
    {
        return $this->collection->rewind();
    }

    public function valid()
    {
        return $this->collection->valid() ? true : false;
    }
}
