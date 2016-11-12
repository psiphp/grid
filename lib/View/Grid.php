<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

final class Grid
{
    private $classFqn;
    private $table;
    private $paginator;
    private $filterBar;
    private $actionBar;
    private $name;

    public function __construct(
        string $classFqn,
        string $name,
        Table $table,
        Paginator $paginator,
        FilterBar $filterBar,
        ActionBar $actionBar
    ) {
        $this->table = $table;
        $this->paginator = $paginator;
        $this->filterBar = $filterBar;
        $this->actionBar = $actionBar;
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
        return $this->filterBar;
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function getActionBar()
    {
        return $this->actionBar;
    }
}
