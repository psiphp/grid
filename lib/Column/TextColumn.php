<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextColumn extends AbstractColumn
{
    public function buildCell(Cell $cell, array $options)
    {
        $cell->parameters['truncate'] = $options['truncate'];
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('truncate', null);
    }

    public function getParent()
    {
        return PropertyColumn::class;
    }
}
