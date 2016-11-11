<?php

namespace Psi\Component\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface CellInterface
{
    public function createView($data, array $options): CellViewInterface;

    public function configureOptions(OptionsResolver $options);
}
