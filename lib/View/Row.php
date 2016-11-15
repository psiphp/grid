<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\CellFactory;
use Psi\Component\Grid\CellViewInterface;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\RowData;

class Row implements \Iterator
{
    private $cellFactory;
    private $columnMetadatas;
    private $data;

    public function __construct(
        CellFactory $cellFactory,
        GridMetadata $gridMetadata,
        $data
    ) {
        $this->cellFactory = $cellFactory;
        $this->columnMetadatas = $gridMetadata->getColumns();
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function current(): CellViewInterface
    {
        $columnMetadata = current($this->columnMetadatas);

        return $this->cellFactory->create(
            $this->key(),
            $columnMetadata->getType(),
            RowData::fromObject($this->data),
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