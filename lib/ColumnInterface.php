<?php

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\Grid\RowData;
use Psi\Component\Grid\CellInterface;

interface ColumnInterface
{
    public function createCell(RowData $data, array $options): CellInterface;

    public function configureOptions(OptionsResolver $options);
}
