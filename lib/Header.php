<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class Header
{
    private $name;
    private $options;

    public function __construct(string $name, GridContext $options)
    {
        $this->name = $name;
        $this->options = $options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isSorted(): bool
    {
        $ordering = $this->options->getOrderings();

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

        return $this->options->getOrderings()[$this->name] === 'asc';
    }

    public function getUrlParametersForSort($order = 'asc')
    {
        $options = $this->options->getUrlParameters();
        $options['orderings'] = [
            $this->name => $order,
        ];

        return $options;
    }
}
