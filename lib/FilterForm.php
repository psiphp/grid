<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\Form\FormView;

final class FilterForm
{
    private $form;

    public function __construct(FormView $form)
    {
        $this->form = $form;
    }

    public function getForm(): FormView
    {
        return $this->form;
    }
}
