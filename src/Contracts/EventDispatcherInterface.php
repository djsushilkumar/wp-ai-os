<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Event Dispatcher Interface contract for domain events and filters.
 */
interface EventDispatcherInterface
{
    /**
     * Subscribe a listener callback to a named domain event.
     *
     * @param string   $eventName
     * @param callable $listener
     * @return void
     */
    public function listen(string $eventName, callable $listener): void;

    /**
     * Dispatch an event payload to registered listeners and WordPress action hooks.
     *
     * @param string $eventName
     * @param mixed  ...$payload
     * @return void
     */
    public function dispatch(string $eventName, mixed ...$payload): void;

    /**
     * Filter a value through listeners and WordPress filter hooks.
     *
     * @param string $filterName
     * @param mixed  $value
     * @param mixed  ...$args
     * @return mixed
     */
    public function filter(string $filterName, mixed $value, mixed ...$args): mixed;
}
