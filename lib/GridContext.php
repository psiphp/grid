<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

final class GridContext
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $classFqn;

    public function __construct(string $classFqn, array $options)
    {
        $this->classFqn = $classFqn;
        $defaults = [
            'page_size' => 50,
            'page' => 1,
            'orderings' => [],
            'filter' => [],
            'variant' => null,
        ];

        // check for invalid keys
        if ($diff = array_diff(array_keys($options), array_keys($defaults))) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid grid context options "%s". Valid options: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaults))
            ));
        }

        // set defaults
        $options = array_merge($defaults, $options);

        // normalize the orderings
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

        // cast integer values where applicable
        foreach (['page', 'page_size'] as $key) {
            $options[$key] = $options[$key] !== null ? (int) $options[$key] : null;
        }

        // ensure current page is > 0
        if ($options['page'] < 1) {
            $options['page'] = 1;
        }

        $this->options = $options;
    }

    public function getCurrentPage(): int
    {
        return $this->options['page'];
    }

    public function isPaginated(): bool
    {
        return null !== $this->options['page_size'];
    }

    public function getPageSize(): int
    {
        return $this->options['page_size'];
    }

    public function getPageOffset(): int
    {
        return ($this->getCurrentPage() - 1) * $this->getPageSize();
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

    public function getUrlParameters(): array
    {
        return array_merge([
            'class' => $this->classFqn,
        ], $this->options);
    }

    public function getClassFqn(): string
    {
        return $this->classFqn;
    }
}
