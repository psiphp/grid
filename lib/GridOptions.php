<?php

namespace Psi\Component\Grid;

final class GridOptions
{
    private $variant;
    private $page;
    private $pageSize;
    private $orderings;

    public function __construct(array $options)
    {
        $defaults = [
            'page_size' => 50,
            'page' => 0,
            'orderings' => [],
            'variant' => null,
        ];

        if ($diff = array_diff(array_keys($options), array_keys($defaults))) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid grid options "%s". Valid options: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaults))
            ));
        }

        $options = array_merge($defaults, $options);

        $this->page = $options['page'];
        $this->pageSize = $options['page_size'];
        $this->orderings = $options['orderings'];
        $this->variant = $options['variant'];
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getPageOffset()
    {
        return $this->page * $this->pageSize;
    }

    public function getOrderings()
    {
        return $this->orderings;
    }

    public function getVariant()
    {
        return $this->variant;
    }
}
