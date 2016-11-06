<?php

namespace Psi\Component\Grid;

use Psi\Component\View\ViewFactory;
use Psi\Component\Grid\Metadata\GridMetadata;

class Grid implements \Iterator
{
    private $viewFactory;
    private $gridMetadata;
    private $collection;

    public function __construct(
        ViewFactory $viewFactory,
        GridMetadata $gridMetadata,
        \Traversable $collection
    )
    {
        $this->viewFactory = $viewFactory;
        $this->gridMetadata = $gridMetadata;
        $this->collection = $collection;
    }

    public function current()
    {
        return new Row(
            $this->viewFactory,
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
        return $this->collection->first();
    }

    public function valid()
    {
        return $this->collection->current() ? true : false;
    }
}
