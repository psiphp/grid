<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;

class BooleanFilterData implements FilterDataInterface
{
    const ANY_CHOICE = 'any';

    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isApplicable(): bool
    {
        return $this->value !== null && $this->value !== self::ANY_CHOICE;
    }
}
