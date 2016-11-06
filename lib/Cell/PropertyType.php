<?php

namespace Psi\Component\Grid\Cell;

use Psi\Component\View\TypeInterface;
use Psi\Component\View\ViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\View\ViewFactory;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyType implements TypeInterface
{
    public function createView(ViewFactory $factory, $data, array $options): ViewInterface
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $property = $options['property'];
        $value = $accessor->getValue($data, $property);

        return new PropertyView($value);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['property']);
        $options->setAllowedTypes('property', [ 'string' ]);
    }
}
