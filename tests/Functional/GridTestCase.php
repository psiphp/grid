<?php

namespace Psi\Component\Grid\Tests\Functional;

use PhpBench\DependencyInjection\Container;

abstract class GridTestCase extends \PHPUnit_Framework_TestCase
{
    protected function createContainer(array $config)
    {
        $container = new Container([
            TestExtension::class,
        ], $config);
        $container->init();

        return $container;
    }
}
