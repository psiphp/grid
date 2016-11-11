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
        FilterBar $filter
    ) {
        $this->table = $table;
        $this->paginator = $paginator;
        $this->filter = $filter;
        $this->classFqn = $classFqn;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClassFqn(): string
    {
        return $this->classFqn;
    }

    public function getFilter(): FilterBar
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
