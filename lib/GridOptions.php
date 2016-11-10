<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

final class GridOptions
{
    private $options;

    public function __construct(array $options)
    {
        $defaults = [
            'page_size' => 50,
            'current_page' => 0,
            'orderings' => [],
            'filter' => [],
            'variant' => null,
        ];

        if ($diff = array_diff(array_keys($options), array_keys($defaults))) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid grid options "%s". Valid options: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaults))
            ));
        }

        $options = array_merge($defaults, $options);
        $options['orderings'] = array_map(function ($order) {
            $order = strtolower($order);

            if (false === in_array($order, ['asc', 'desc'])) {
                throw new \InvalidArgumentException(sprintf(
                    'Order must be either "asc" or "desc" got "%s"',
                    $order
                ));
            }

            return $order;
        }, $options['orderings']);

        $this->options = $options;
    }

    public function getCurrentPage(): int
    {
        return $this->options['current_page'];
    }

    public function getPageSize(): int
    {
        return $this->options['page_size'];
    }

    public function getPageOffset(): int
    {
        return $this->getCurrentPage() * $this->getPageSize();
    }

    public function getOrderings(): array
    {
        return $this->options['orderings'];
    }

    public function getVariant()
    {
        return $this->options['variant'];
    }

    public function getFilter()
    {
        return $this->options['filter'];
    }

    public function getFilterActionOptions(): array
    {
        return array_filter($this->options, function ($key) {
            return $key !== 'filter';
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
