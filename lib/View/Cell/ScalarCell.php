<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View\Cell;

use Psi\Component\Grid\CellInterface;

class ScalarCell implements CellInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
