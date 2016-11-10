<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class Header
{
    private $name;
    private $sorted;
    private $isSortAscending;

    public function __construct(string $name, bool $sorted, bool $isSortAscending)
    {
        $this->name = $name;
        $this->sorted = $sorted;
        $this->isSortAscending = $isSortAscending;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isSorted(): bool
    {
        return $this->sorted;
    }

    public function isSortAscending(): bool
    {
        return $this->isSortAscending;
    }
}
