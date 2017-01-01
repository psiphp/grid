<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyColumn extends AbstractColumn
{
    public function buildCell(Cell $cell, array $options)
    {
        $value = $cell->value;

        if (null === $value) {
            return;
        }

        if (false === is_int($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Money column requires an integer value, got "%s"',
                gettype($value)
            ));
        }

        $cell->value = $cell->value / $options['divisor'];
        $cell->value = number_format($cell->value, $options['scale']);
        $cell->parameters['currency'] = $options['currency'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('currency', 'EUR');
        $resolver->setDefault('divisor', 1);
        $resolver->setDefault('scale', 2);
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
