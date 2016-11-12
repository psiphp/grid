<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class GridMetadata
{
    private $name;
    private $columns;
    private $pageSize;
    private $filters;
    private $actions;
    private $classMetadata;

    public function __construct(string $name, array $columns, array $filters, array $actions, int $pageSize)
    {
        $this->name = $name;
        $this->columns = $columns;
        $this->filters = $filters;
        $this->actions = $actions;
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

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function attachClassMetadata(ClassMetadata $classMetadata)
    {
        if ($this->classMetadata) {
            throw new \RuntimeException(
                'Class metadata has already been attached to grid metadata.'
            );
        }

        $this->classMetadata = $classMetadata;
    }

    public function getClassMetadata()
    {
        if (!$this->classMetadata) {
            throw new \RuntimeException('Class metadata has not been attached to grid.');
        }

        return $this->classMetadata;
    }
}
