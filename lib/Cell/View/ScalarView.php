<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell\View;

use Psi\Component\Grid\CellViewInterface;

class ScalarView implements CellViewInterface
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
