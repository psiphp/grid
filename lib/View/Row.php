<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\RowData;

class Row implements \Iterator
{
    private $cellFactory;
    private $columnMetadatas;
    private $data;

    public function __construct(
        ColumnFactory $cellFactory,
        GridMetadata $gridMetadata,
        GridContext $gridContext,
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

    public function current(): CellInterface
    {
        $columnMetadata = current($this->columnMetadatas);

        return $this->cellFactory->createCell(
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
