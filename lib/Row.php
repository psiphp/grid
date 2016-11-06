<?php

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\View\ViewFactory;
use Psi\Component\View\ViewInterface;

class Row implements \Iterator
{
    private $viewFactory;
    private $columnMetadatas;
    private $data;

    public function __construct(
        ViewFactory $viewFactory,
        GridMetadata $gridMetadata,
        $data
    ) {
        $this->viewFactory = $viewFactory;
        $this->columnMetadatas = $gridMetadata->getColumns();
        $this->data = $data;
    }

    public function current(): ViewInterface
    {
        $columnMetadata = current($this->columnMetadatas);

        return $this->viewFactory->create(
            $columnMetadata->getType(),
            $this->data,
            array_merge([
                'column_name' => $this->key(),
            ], $columnMetadata->getOptions())
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
