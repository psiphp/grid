<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractColumn implements ColumnInterface
{
    public function buildCell(Cell $cell, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getParent()
    {
    }
}

