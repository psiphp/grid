<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class GridMetadata
{
    private $name;
    private $columns;
    private $pageSize;
    private $filters;

    public function __construct(string $name, array $columns, array $filters, int $pageSize)
    {
        $this->name = $name;
        $this->columns = $columns;
        $this->filters = $filters;
        $this->pageSize = $pageSize;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
