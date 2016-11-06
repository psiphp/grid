<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\Form\FormView;

final class FilterForm
{
    private $formView;

    public function __construct(FormView $formView)
    {
        $this->formView = $formView;
    }

    public function getFormView(): FormView
    {
        return $this->formView;
    }
}
