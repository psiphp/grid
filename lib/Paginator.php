<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class Paginator
{
    private $options;
    private $numberOfRecords;
    private $numberOfRecordsOnPage;

    public function __construct(GridContext $options, int $numberOfRecordsOnPage, int $numberOfRecords = null)
    {
        $this->options = $options;
        $this->numberOfRecords = $numberOfRecords;
        $this->numberOfRecordsOnPage = $numberOfRecordsOnPage;
    }

    public function getPageSize(): int
    {
        return $this->options->getPageSize();
    }

    public function getCurrentPage(): int
    {
        return $this->options->getCurrentPage();
    }

    public function getNumberOfRecords()
    {
        return $this->numberOfRecords;
    }

    public function getNumberOfPages(): int
    {
        if (null === $this->numberOfRecords) {
            throw new \RuntimeException(
                'Cannot determine the last page when the total number of ' .
                'records has not been provided.'
            );
        }

        return (int) ceil($this->getNumberOfRecords() / $this->getPageSize());
    }

    public function isLastPage(): bool
    {
        if (null === $this->numberOfRecords) {
            return $this->numberOfRecordsOnPage < $this->getPageSize();
        }

        return $this->getCurrentPage() == $this->getNumberOfPages();
    }

    public function getUrlParametersForPage($page = null)
    {
        return array_merge($this->options->getUrlParameters(), [
            'page' => $page ?: $this->options->getCurrentPage(),
        ]);
    }
}
