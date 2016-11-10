<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\Form\FormView;

class FilterForm
{
    private $form;
    private $options;

    public function __construct(FormView $form, GridContext $options)
    {
        $this->form = $form;
        $this->options = $options;
    }

    public function getForm(): FormView
    {
        return $this->form;
    }

    public function getUrlParametersForFilter()
    {
        return array_filter($this->options->getUrlParameters(), function ($key) {
            return $key !== 'filter';
        }, ARRAY_FILTER_USE_KEY);
    }
}
