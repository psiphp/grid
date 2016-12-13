<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\ColumnRegistry;
use Psi\Component\Grid\View\Cell;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ColumnTestCase extends \PHPUnit_Framework_TestCase
{
    public function buildCell(ColumnInterface $column, Cell $cell, array $options = [])
    {
        $resolver = new OptionsResolver();
        $column->configureOptions($resolver);
        $defaultOptions = $resolver->resolve([]);
        $options = array_merge($defaultOptions, $options);

        $column->buildCell($cell, $options);
    }

    protected function createFactory()
    {
        $registry = new ColumnRegistry();
        foreach ($this->getColumns() as $column) {
            $registry->register($column);
        }

        return new ColumnFactory($registry);
    }

    abstract protected function getColumns(): array;
}
