<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\Metadata\GridMetadata;

class Table
{
    private $cellFactory;
    private $gridMetadata;
    private $collection;
    private $context;

    public function __construct(
        ColumnFactory $cellFactory,
        GridMetadata $gridMetadata,
        \Iterator $collection,
        GridContext $context
    ) {
        $this->cellFactory = $cellFactory;
        $this->gridMetadata = $gridMetadata;
        $this->collection = $collection;
        $this->context = $context;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach (array_keys($this->gridMetadata->getColumns()) as $headerName) {
            $headers[$headerName] = new Header($headerName, $this->context);
        }

        return $headers;
    }

    public function getBody()
    {
        return new Body(
            $this->cellFactory,
            $this->gridMetadata,
            $this->collection
        );
    }
}
