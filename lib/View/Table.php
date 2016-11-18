<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;

class Table
{
    private $columnFactory;
    private $gridMetadata;
    private $collection;
    private $gridContext;

    public function __construct(
        ColumnFactory $columnFactory,
        GridMetadata $gridMetadata,
        GridContext $gridContext,
        \Iterator $collection
    ) {
        $this->columnFactory = $columnFactory;
        $this->gridMetadata = $gridMetadata;
        $this->collection = $collection;
        $this->gridContext = $gridContext;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($this->gridMetadata->getColumns() as $columnName => $column) {
            $headers[$columnName] = $this->columnFactory->createHeader(
                $this->gridContext,
                $columnName,
                $column->getType(),
                $column->getOptions()
            );
        }

        return $headers;
    }

    public function getBody()
    {
        return new Body(
            $this->columnFactory,
            $this->gridMetadata,
            $this->gridContext,
            $this->collection
        );
    }
}
