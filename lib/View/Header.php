<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\GridContext;

class Header
{
    private $name;
    private $label;
    private $template;
    private $gridContext;
    private $sortField;

    public function __construct(GridContext $gridContext, string $template, string $name, string $label, string $sortField = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->template = $template;
        $this->gridContext = $gridContext;
        $this->sortField = $sortField;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
    
    public function isSorted(): bool
    {
        $ordering = $this->gridContext->getOrderings();

        return isset($ordering[$this->name]);
    }

    public function isSortAscending(): bool
    {
        if (false === $this->isSorted()) {
            throw new \RuntimeException(sprintf(
                'Cannot determine if sort is ascending when the field ("%s") is not sorted.',
                $this->name
            ));
        }

        return $this->gridContext->getOrderings()[$this->name] === 'asc';
    }

    public function getSortField(): string
    {
        return $this->sortField;
    }

    public function canBeSorted(): bool
    {
        return null !== $this->sortField;
    }

    public function getUrlParametersForSort($order = 'asc'): array
    {
        $gridContext = $this->gridContext->getUrlParameters();
        $gridContext['orderings'] = [
            $this->name => $order,
        ];

        return $gridContext;
    }
}
