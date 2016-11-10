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
    private $orderings;

    public function __construct(
        ViewFactory $viewFactory,
        GridMetadata $gridMetadata,
        \Traversable $collection,
        array $orderings
    ) {
        $this->viewFactory = $viewFactory;
        $this->gridMetadata = $gridMetadata;
        $this->collection = $collection;
        $this->orderings = $orderings;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach(array_keys($this->gridMetadata->getColumns()) as $headerName) {
            $sort = null;
            if (isset($this->orderings[$headerName])) {
                $sort = $this->orderings[$headerName];
            }

            $headers[$headerName] = new Header($headerName, null !== $sort, $sort === 'asc');
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
