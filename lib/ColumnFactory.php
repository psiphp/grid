<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\View\Header;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnFactory
{
    private $registry;

    public function __construct(
        ColumnRegistry $registry

    ) {
        $this->registry = $registry;
    }

    public function createCell(string $columnName, string $typeName, RowData $data, array $options): CellInterface
    {
        $column = $this->registry->get($typeName);
        $options = $this->resolveOptions($columnName, $column, $options);

        return $column->createCell($data, $options);
    }

    public function createHeader(GridContext $gridContext, string $columnName, string $typeName, array $options): Header
    {
        $column = $this->registry->get($typeName);
        $options = $this->resolveOptions($columnName, $column, $options);

        return $column->createHeader($gridContext, $options);
    }

    private function resolveOptions($columnName, ColumnInterface $column, array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('column_name', $columnName);
        $column->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $options;
    }
}
