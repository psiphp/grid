<?php

declare(strict_types=1);

declare(strict_types=1);

namespace Psi\Component\Grid\Cell;

use Psi\Component\View\ViewInterface;

class ScalarView implements ViewInterface
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
