<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

final class Grid
{
    private $classFqn;
    private $table;
    private $paginator;
    private $filter;
    private $name;

    public function __construct(
        string $classFqn,
        string $name,
        Table $table,
        Paginator $paginator,
        FilterForm $filter
    ) {
        $this->name = $name;
        $this->table = $table;
        $this->paginator = $paginator;
        $this->filter = $filter;
        $this->classFqn = $classFqn;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClassFqn(): string
    {
        return $this->classFqn;
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
