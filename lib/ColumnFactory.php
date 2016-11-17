<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnFactory
{
    private $registry;

    public function __construct(
        ColumnRegistry $registry

    ) {
        $this->registry = $registry;
    }

	// TODO: Rename to createCell -- add createHeader
    public function create(string $columnName, string $typeName, RowData $data, array $options): CellInterface
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('column_name', $columnName);
        $type = $this->registry->get($typeName);
        $type->configureOptions($resolver);

        $options = $resolver->resolve($options);

        return $type->createCell($data, $options);
    }
}
