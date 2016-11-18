<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View\Cell;

use Psi\Component\Grid\CellInterface;

class ScalarCell implements CellInterface
{
    private $value;
    private $view;

    public function __construct(string $view = null, $value)
    {
        $this->value = $value;
        $this->view = $view;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getView()
    {
        return $this->view;
    }
}
