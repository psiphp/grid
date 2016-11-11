<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\View\ViewFactory;

class Table
{
    private $viewFactory;
    private $gridMetadata;
    private $collection;
    private $context;

    public function __construct(
        ViewFactory $viewFactory,
        GridMetadata $gridMetadata,
        \Traversable $collection,
        GridContext $context
    ) {
        $this->viewFactory = $viewFactory;
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
            $this->viewFactory,
            $this->gridMetadata,
            $this->collection
        );
    }
}
