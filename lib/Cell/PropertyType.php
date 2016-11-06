<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell;

use Psi\Component\View\ViewFactory;
use Psi\Component\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyType extends CellType
{
    public function createView(ViewFactory $factory, $data, array $options): ViewInterface
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $property = $options['property'];
        $value = $accessor->getValue($data, $property);

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
