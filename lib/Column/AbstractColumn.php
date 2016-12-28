<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\Util\ColumnTemplateInferrer;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
