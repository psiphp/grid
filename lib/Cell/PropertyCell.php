<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\CellViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyCell implements CellInterface
{
    private $accessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function createView($data, array $options): CellViewInterface
    {
        $property = $options['property'];
        $value = $this->accessor->getValue($data, $property);

        return new View\ScalarView($value);
    }

    public function configureOptions(OptionsResolver $options)
    {
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
