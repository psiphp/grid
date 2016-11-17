<?php

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnInterface
{
    public function createCell(RowData $data, array $options): CellInterface;

    public function configureOptions(OptionsResolver $options);
}
