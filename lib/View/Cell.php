<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

class Cell
{
    public $value;
    public $parameters = [];

    private $context;
    private $template;

    public function __construct($context, string $template)
    {
        $this->context = $context;
        $this->template = $template;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
