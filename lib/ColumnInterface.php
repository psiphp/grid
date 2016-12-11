<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnInterface
{
    public function createCell(RowData $data, array $options): CellInterface;

    public function createHeader(GridContext $context, array $options);

    public function configureOptions(OptionsResolver $options);
}
