<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;

class StringFilterData implements FilterDataInterface
{
    private $comparator;
    private $value;

    public function __construct(string $comparator = null, string $value = null)
    {
        $this->comparator = $comparator;
        $this->value = $value;
    }

    public function getComparator()
    {
        return $this->comparator;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isApplicable(): bool
    {
        return null !== $this->value;
    }
}
