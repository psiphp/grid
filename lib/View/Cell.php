<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

class Cell
{
    private $template;

    public $context;
    public $value;
    public $parameters = [];

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}
