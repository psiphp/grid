<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;

class NumberFilterData implements FilterDataInterface
{
    private $comparator;
    private $value;

    public function __construct(string $comparator = null, $value = null)
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
}
