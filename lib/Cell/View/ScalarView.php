<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell\View;

use Psi\Component\Grid\CellViewInterface;

class ScalarView implements CellViewInterface
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
