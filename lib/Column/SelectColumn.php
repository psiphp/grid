<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectColumn implements ColumnInterface
{
    const INPUT_NAME = '_select';

    public function buildCell(Cell $cell, array $options)
    {
        $cell->template = 'Select';
        $cell->parameters['input_name'] = self::INPUT_NAME;
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('property', 'id');
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
