<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

final class Grid
{
    private $table;
    private $paginator;
    private $filter;

    public function __construct(
        Table $table,
        Paginator $paginator,
        FilterForm $filter
    ) {
        $this->table = $table;
        $this->paginator = $paginator;
        $this->filter = $filter;
    }

    public function getFilter(): FilterForm
    {
        return $this->filter;
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
