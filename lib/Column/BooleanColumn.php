<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanColumn extends AbstractColumn
{
    public function buildCell(Cell $cell, array $options)
    {
        $cell->value = (bool) $cell->value;
        $cell->template = 'Boolean';
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
