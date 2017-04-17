<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;

class Row implements \Iterator
{
    private $columnFactory;
    private $columnMetadatas;
    private $data;

    public function __construct(
        ColumnFactory $columnFactory,
        GridMetadata $gridMetadata,
        GridContext $gridContext,
        $data
    ) {
        $this->columnFactory = $columnFactory;
        $this->columnMetadatas = $gridMetadata->getColumnsForGroups($gridContext->getGroups());
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function current(): Cell
    {
        $columnMetadata = current($this->columnMetadatas);

        return $this->columnFactory->createCell(
            $this->key(),
            $columnMetadata->getType(),
            $this->data,
            $columnMetadata->getOptions()
        );
    }

    public function next()
    {
        return next($this->columnMetadatas);
    }

    public function key()
    {
        return key($this->columnMetadatas);
    }

    public function rewind()
    {
        return reset($this->columnMetadatas);
    }

    public function valid()
    {
        return current($this->columnMetadatas) !== false;
    }
}
