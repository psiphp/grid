<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\RowData;
use Psi\Component\Grid\View\Cell as Cell;
use Psi\Component\Grid\View\Header;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumn implements ColumnInterface
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function createCell(RowData $data, array $options): CellInterface
    {
        $property = $options['property'];

        // if the data is an object, then the accssor should act directly on
        // that rather than accessing the RowData object directly.
        if (false === $data->isArrayLike()) {
            $data = $data->getData();
        }

        $value = $this->accessor->getValue($data, $property);

        return new Cell\ScalarCell($options['view'], $value);
    }

    public function createHeader(GridContext $context, array $options)
    {
        return new Header($context, $options['column_name'], $options['property']);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('view', null);
        $options->setDefault('property', null);
        $options->setAllowedTypes('property', ['string', 'null']);
        $options->setAllowedTypes('view', ['string', 'null']);
        $options->setNormalizer('property', function (OptionsResolver $options, $value) {
            if (null !== $value) {
                return $value;
            }

            return $options['column_name'];
        });
    }
}
