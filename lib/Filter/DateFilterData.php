<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;

class DateFilterData implements FilterDataInterface
{
    private $value;
    private $apply;
    private $comparator;

    public function __construct($apply, $comparator = null, $value = null)
    {
        $this->value = $value;
        $this->apply = $apply;
        $this->comparator = $comparator;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getComparator()
    {
        return $this->comparator;
    }

    public function getApply()
    {
        return $this->apply;
    }

    public function isApplicable(): bool
    {
        return $this->apply;
    }
}
