<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumn extends AbstractColumn
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function buildCell(Cell $cell, array $options)
    {
        $property = $options['property'];
        $cell->template = 'Property';

        // if the column name is the same as the property name, then assume that
        // the user has not overridden the property and, if the context is an array,
        // access it as such.
        if ($options['column_name'] === $options['property'] && false === is_object($cell->context)) {
            $property = '[' . $property . ']';
        }

        $cell->value = $this->accessor->getValue($cell->context, $property);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('header_template', 'Property');
        $options->setDefault('property', null);
        $options->setAllowedTypes('property', ['string', 'null']);
        $options->setNormalizer('property', function (OptionsResolver $options, $value) {
            if (null !== $value) {
                return $value;
            }

            return $options['column_name'];
        });
    }
}
