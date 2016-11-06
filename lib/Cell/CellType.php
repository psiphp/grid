<?php

namespace Psi\Component\Grid\Cell;

use Psi\Component\View\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CellType implements TypeInterface
{
    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['column_name']);
    }
}
