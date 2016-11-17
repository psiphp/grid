<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\RowData;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Psi\Component\Grid\CellInterface;

class PropertyColumn implements ColumnInterface
{
    private $accessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function createCell(RowData $data, array $options): CellInterface
    {
        $property = $options['property'];
        $value = $this->accessor->getValue($data->getObject(), $property);

        return new Cell\ScalarCell($options['variant'], $value);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('variant', null);
        $options->setDefault('property', null);
        $options->setAllowedTypes('property', ['string', 'null']);
        $options->setAllowedTypes('variant', ['string', 'null']);
        $options->setNormalizer('property', function (OptionsResolver $options, $value) {
            if (null !== $value) {
                return $value;
            }

            return $options['column_name'];
        });
    }
}
