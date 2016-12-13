<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\View\Cell;
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

    public function createCell(string $columnName, string $typeName, $data, array $options): Cell
    {
        $column = $this->registry->get($typeName);

        $cell = new Cell();
        $cell->context = $data;

        $layers = $this->resolveLayers($column);
        $options = $this->resolveOptions($layers, $columnName, $options);

        foreach ($layers as $column) {
            $column->buildCell($cell, $options);
        }

        return $cell;
    }

    public function createHeader(GridContext $gridContext, string $columnName, string $typeName, array $options): Header
    {
        $column = $this->registry->get($typeName);
        $options = $this->resolveOptions($this->resolveLayers($column), $columnName, $options);

        return new Header($gridContext, $options['column_name'], $options['sort_field']);
    }

    private function resolveOptions(array $layers, $columnName, array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('column_name', $columnName);
        $resolver->setDefault('sort_field', null);

        foreach ($layers as $column) {
            $column->configureOptions($resolver);
        }

        return $resolver->resolve($options);
    }

    private function resolveLayers(ColumnInterface $column)
    {
        $layers = [$column];
        $seen = [spl_object_hash($column) => true];

        while (null !== $parentType = $column->getParent()) {
            $parent = $this->registry->get($parentType);

            if (isset($seen[spl_object_hash($parent)])) {
                throw new \RuntimeException(sprintf(
                    'Circular reference detected on class "%s"',
                    get_class($parent)
                ));
            }

            $layers[] = $parent;
            $column = $parent;
        }

        return array_reverse($layers);
    }
}
