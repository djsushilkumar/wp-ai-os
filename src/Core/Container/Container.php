<?php

declare(strict_types=1);

namespace WPAIOS\Core\Container;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\ServiceProviderInterface;

/**
 * Enterprise Dependency Injection Container supporting Singleton, Scoped, and Transient lifetimes.
 */
class Container implements ContainerInterface
{
    /**
     * @var array<string, array{concrete: mixed, lifetime: string}>
     */
    private array $bindings = [];

    /**
     * @var array<string, object>
     */
    private array $singletons = [];

    /**
     * @var array<string, object>
     */
    private array $scopedInstances = [];

    /**
     * @var ServiceProviderInterface[]
     */
    private array $loadedProviders = [];

    public const LIFETIME_TRANSIENT = 'transient';
    public const LIFETIME_SINGLETON = 'singleton';
    public const LIFETIME_SCOPED    = 'scoped';

    /**
     * Bind a transient interface/abstract key to a concrete class or closure factory.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function bind(string $abstract, mixed $concrete = null): void
    {
        $this->registerBinding($abstract, $concrete, self::LIFETIME_TRANSIENT);
    }

    /**
     * Bind a singleton interface/abstract key.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function singleton(string $abstract, mixed $concrete = null): void
    {
        $this->registerBinding($abstract, $concrete, self::LIFETIME_SINGLETON);
    }

    /**
     * Bind a scoped service instance (reset on request scope completion).
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function scoped(string $abstract, mixed $concrete = null): void
    {
        $this->registerBinding($abstract, $concrete, self::LIFETIME_SCOPED);
    }

    /**
     * Bind an instantiated object instance directly as a singleton.
     *
     * @param string $abstract
     * @param object $instance
     * @return void
     */
    public function instance(string $abstract, object $instance): void
    {
        $this->singletons[ $abstract ] = $instance;
    }

    /**
     * Register a Service Provider with the container.
     *
     * @param ServiceProviderInterface $provider
     * @return void
     */
    public function registerProvider(ServiceProviderInterface $provider): void
    {
        $provider->register();
        $this->loadedProviders[] = $provider;
    }

    /**
     * Boot all registered Service Providers.
     *
     * @return void
     */
    public function bootProviders(): void
    {
        foreach ($this->loadedProviders as $provider) {
            $provider->boot();
        }
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
        if (isset($this->singletons[ $id ])) {
            return $this->singletons[ $id ];
        }

        if (isset($this->scopedInstances[ $id ])) {
            return $this->scopedInstances[ $id ];
        }

        if (! isset($this->bindings[ $id ])) {
            if (class_exists($id)) {
                return $this->build($id);
            }
            throw new Exception(sprintf('Target [%s] is not bound in container.', $id));
        }

        $binding  = $this->bindings[ $id ];
        $concrete = $binding['concrete'];
        $lifetime = $binding['lifetime'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = $this->build($concrete);
        } else {
            $object = $concrete;
        }

        if (is_object($object)) {
            if ($lifetime === self::LIFETIME_SINGLETON) {
                $this->singletons[ $id ] = $object;
            } elseif ($lifetime === self::LIFETIME_SCOPED) {
                $this->scopedInstances[ $id ] = $object;
            }
        }

        return $object;
    }

    /**
     * Check if the container can resolve an entry.
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->singletons[ $id ]) ||
                isset($this->scopedInstances[ $id ]) ||
                isset($this->bindings[ $id ]) ||
                class_exists($id);
    }

    /**
     * Forget a specific instance.
     *
     * @param string $abstract
     * @return void
     */
    public function forget(string $abstract): void
    {
        unset($this->singletons[ $abstract ], $this->scopedInstances[ $abstract ]);
    }

    /**
     * Clear all current request scope instances.
     *
     * @return void
     */
    public function clearScope(): void
    {
        $this->scopedInstances = [];
    }

    /**
     * Helper to store binding definitions.
     */
    private function registerBinding(string $abstract, mixed $concrete, string $lifetime): void
    {
        if (null === $concrete) {
            $concrete = $abstract;
        }

        $this->bindings[ $abstract ] = [
            'concrete' => $concrete,
            'lifetime' => $lifetime,
        ];
    }

    /**
     * Auto-wire constructor dependencies via PHP Reflection.
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

    public function alias(string $alias, string $abstract): void
    {
        $this->bind($alias, $abstract);
    }
}
