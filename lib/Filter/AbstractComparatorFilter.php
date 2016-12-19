<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractComparatorFilter implements FilterInterface
{
    abstract protected function getComparatorMap();

    protected function addComparatorChoice(FormBuilderInterface $builder, array $options)
    {
        if (count($options['comparators']) > 1) {
            $builder->add('comparator', ChoiceType::class, [
                'choices' => $this->getChoices(
                    $options['capabilities']->getSupportedComparators(),
                    $options['comparators']
                ),
            ]);

            return;
        }

        $builder->add('comparator', HiddenType::class, [
            'empty_data' => reset($options['comparators']),
        ]);
    }

    private function getChoices(array $supportedComparators, array $enabledComparators)
    {
        $supported = array_keys(array_filter($this->getComparatorMap(), function ($comparator) use ($supportedComparators) {
            return in_array($comparator, $supportedComparators);
        }));

        $supported = array_filter($supported, function ($comparator) use ($enabledComparators) {
            return in_array($comparator, $enabledComparators);
        });

        return array_combine($supported, $supported);
    }
}
