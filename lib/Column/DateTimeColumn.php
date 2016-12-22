<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeColumn extends AbstractColumn
{
    public function buildCell(Cell $cell, array $options)
    {
        $value = $cell->value;

        if (null === $value) {
            return;
        }

        if (false === $value instanceof \DateTime) {
            throw new \InvalidArgumentException(sprintf(
                'The DateTime column requires a \DateTime value at "%s", but got "%s"',
                $options['property'], is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $cell->parameters['format'] = $options['format'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('format', 'c');
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
