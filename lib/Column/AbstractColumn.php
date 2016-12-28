<?php

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\Grid\Util\ColumnTemplateInferrer;

abstract class AbstractColumn implements ColumnInterface
{
    public function getCellTemplate(): string
    {
        return ColumnTemplateInferrer::inferCellTemplate(get_class($this));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getParent()
    {
    }
}
