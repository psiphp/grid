<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\View\Cell;

class BooleanColumn extends AbstractColumn
{
    public function buildCell(Cell $cell, array $options)
    {
        $cell->value = (bool) $cell->value;
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
