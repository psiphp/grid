<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnInterface
{
    public function buildCell(Cell $cell, array $options);

    public function configureOptions(OptionsResolver $options);

    public function getParent();

    public function getCellTemplate(): string;
}
