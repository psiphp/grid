<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class Paginator
{
    private $pageSize;
    private $currentPage;
    private $numberOfRecords;

    public function __construct(int $pageSize, int $currentPage, int $numberOfRecords = null)
    {
        $this->pageSize = $pageSize;
        $this->currentPage = $currentPage;
        $this->numberOfRecords = $numberOfRecords;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getNumberOfRecords(): int
    {
        return $this->numberOfRecords;
    }

    public function getLastPage(): int
    {
        return (int) ceil($this->numberOfRecords / $this->pageSize);
    }
}
