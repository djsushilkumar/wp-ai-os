<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Dependency Injection Container Interface supporting Singleton, Scoped, and Transient bindings.
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * Bind a transient interface/abstract key to a concrete implementation or factory closure.
     * Re-created on every resolution call.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function bind(string $abstract, mixed $concrete = null): void;

    /**
     * Bind a singleton interface/abstract key.
     * Instantiated once per container lifetime.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function singleton(string $abstract, mixed $concrete = null): void;

    /**
     * Bind a scoped service instance.
     * Instantiated once per request scope lifecycle.
     *
     * @param string $abstract
     * @param mixed  $concrete
     * @return void
     */
    public function scoped(string $abstract, mixed $concrete = null): void;

    /**
     * Bind an instantiated object directly as a singleton instance.
     *
     * @param string $abstract
     * @param object $instance
     * @return void
     */
    public function instance(string $abstract, object $instance): void;

    /**
     * Forget a scoped or singleton instance, triggering re-resolution.
     *
     * @param string $abstract
     * @return void
     */
    public function forget(string $abstract): void;

    /**
     * Clear all scoped instances.
     *
     * @return void
     */
    public function clearScope(): void;

    /**
     * Alias an abstract target.
     *
     * @param string $alias
     * @param string $abstract
     * @return void
     */
    public function alias(string $alias, string $abstract): void;
}
