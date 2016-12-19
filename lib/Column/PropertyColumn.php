<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
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

    public function buildCell(Cell $cell, array $options)
    {
        $property = $options['property'];

        if (null === $property) {
            $property = $options['column_name'];

            if (false === is_object($cell->context)) {
                $property = '[' . $property . ']';
            }
        }

        $cell->template = 'Property';

        $cell->value = $this->accessor->getValue($cell->context, $property);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('property', null);
        $options->setAllowedTypes('property', ['string', 'null']);
    }

    public function getParent()
    {
    }
}
