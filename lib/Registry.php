<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class Registry
{
    private $context;
    private $class;
    private $instances = [];
    private $aliases = [];

    public function __construct(string $class, string $context)
    {
        $this->class = $class;
        $this->context = $context;
    }

    public function register($instance, $alias = null)
    {
        if (!is_object($instance)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an object, but got an "%s".',
                gettype($instance)
            ));
        }

        if (false === $instance instanceof $this->class) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an instance of "%s", but got "%s"',
                $this->class, get_class($instance)
            ));
        }

        $classFqn = get_class($instance);

        if (isset($this->instances[$classFqn])) {
            throw new \InvalidArgumentException(sprintf(
                '%s "%s" has already been registered',
                $this->context, $classFqn
            ));
        }

        $this->instances[$classFqn] = $instance;

        if (null !== $alias) {
            if (isset($this->aliases[$alias])) {
                throw new \InvalidArgumentException(sprintf(
                    'Alias "%s" has already been set to "%s"',
                    $alias, $this->aliases[$alias]
                ));
            }

            $this->aliases[$alias] = $classFqn;
        }
    }

    public function get(string $classFqn)
    {
        if (isset($this->aliases[$classFqn])) {
            $classFqn = $this->aliases[$classFqn];
        }

        if (!isset($this->instances[$classFqn])) {
            throw new \InvalidArgumentException(sprintf(
                '%s with name "%s" was not found. Known %s types: "%s", aliases: "%s"',
                $this->context,
                $classFqn,
                $this->context,
                implode('", "', array_keys($this->instances)),
                implode('", "', array_keys($this->aliases))
            ));
        }

        return $this->instances[$classFqn];
    }

    public function has(string $classFqn)
    {
        return isset($this->instances[$classFqn]) || isset($this->aliases[$classFqn]);
    }
}
