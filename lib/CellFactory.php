<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CellFactory
{
    private $registry;

    public function __construct(
        CellRegistry $registry

    ) {
        $this->registry = $registry;
    }

    public function create(string $columnName, string $typeName, RowData $data, array $options): CellViewInterface
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('column_name', $columnName);
        $type = $this->registry->get($typeName);
        $type->configureOptions($resolver);

        $options = $resolver->resolve($options);

        return $type->createView($data, $options);
    }
}
