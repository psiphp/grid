<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View\Cell;

use Psi\Component\Grid\CellInterface;

class ScalarCell implements CellInterface
{
    private $value;
    private $variant;

    public function __construct(string $variant = null, $value)
    {
        $this->value = $value;
        $this->variant = $variant;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariant()
    {
        return $this->variant;
    }
}
