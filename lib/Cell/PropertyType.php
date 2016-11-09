<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell;

use Psi\Component\View\ViewFactory;
use Psi\Component\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyType extends CellType
{
    private $accessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function createView(ViewFactory $factory, $data, array $options): ViewInterface
    {
        $property = $options['property'];
        $value = $this->accessor->getValue($data, $property);

        return new ScalarView($value);
    }

    public function configureOptions(OptionsResolver $options)
    {
        parent::configureOptions($options);

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
