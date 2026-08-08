<?php

declare(strict_types=1);

namespace WPAIOS\Core;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use WPAIOS\Contracts\ContainerInterface;

/**
 * PSR-11 Compliant Lightweight Dependency Injection Container.
 */
class Container implements ContainerInterface
{
    /**
     * @var array<string, array{concrete: mixed, shared: bool}>
     */
    private array $bindings = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * Bind an abstract alias to a concrete implementation or factory closure.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @param bool   $shared
     * @return void
     */
    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        if (null === $concrete) {
            $concrete = $abstract;
        }

        $this->bindings[ $abstract ] = [
            'concrete' => $concrete,
            'shared'   => $shared,
        ];
    }

    /**
     * Register a shared singleton binding.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function singleton(string $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Register an existing instance as a singleton.
     *
     * @param string $abstract
     * @param object $instance
     * @return void
     */
    public function instance(string $abstract, object $instance): void
    {
        $this->instances[ $abstract ] = $instance;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @template T
     * @param class-string<T>|string $id
     * @return mixed
     * @throws Exception
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[ $id ])) {
            return $this->instances[ $id ];
        }

        if (! isset($this->bindings[ $id ])) {
            if (class_exists($id)) {
                return $this->build($id);
            }
            throw new Exception(sprintf('Target [%s] is not bound in container.', $id));
        }

        $binding  = $this->bindings[ $id ];
        $concrete = $binding['concrete'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = $this->build($concrete);
        } else {
            $object = $concrete;
        }

        if ($binding['shared'] && is_object($object)) {
            $this->instances[ $id ] = $object;
        }

        return $object;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->instances[ $id ]) || isset($this->bindings[ $id ]) || class_exists($id);
    }

    /**
     * Instantiate a class with dependency resolution via Reflection.
     *
     * @param string $className
     * @return object
     * @throws Exception
     */
    private function build(string $className): object
    {
        $reflection = new ReflectionClass($className);

        if (! $reflection->isInstantiable()) {
            throw new Exception(sprintf('Class [%s] is not instantiable.', $className));
        }

        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return new $className();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = array_map(
            function (ReflectionParameter $parameter) use ($className) {
                $name = $parameter->getName();
                $type = $parameter->getType();

                if (null === $type) {
                    if ($parameter->isDefaultValueAvailable()) {
                        return $parameter->getDefaultValue();
                    }
                    throw new Exception(sprintf('Cannot resolve un-typed parameter [$%s] in [%s].', $name, $className));
                }

                if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                    $targetClass = $type->getName();
                    return $this->get($targetClass);
                }

                if ($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }

                throw new Exception(sprintf('Cannot resolve parameter [$%s] in [%s].', $name, $className));
            },
            $parameters
        );

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * @var array<string, object>
     */
    private array $scopedInstances = [];

    public function scoped(string $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    public function forget(string $abstract): void
    {
        unset($this->instances[ $abstract ], $this->scopedInstances[ $abstract ]);
    }

    public function clearScope(): void
    {
        $this->scopedInstances = [];
    }

    public function alias(string $alias, string $abstract): void
    {
        $this->bind($alias, $abstract);
    }
}
