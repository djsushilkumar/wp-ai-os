<?php

declare(strict_types=1);

namespace WPAIOS\Core;

use WPAIOS\Contracts\EventDispatcherInterface;

/**
 * Event Dispatcher linking internal WP AI OS domain events to WordPress Action & Filter Hooks.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, array<callable>>
     */
    private array $listeners = [];

    /**
     * Subscribe a listener callback to a named domain event.
     *
     * @param string   $eventName
     * @param callable $listener
     * @return void
     */
    public function listen(string $eventName, callable $listener): void
    {
        $this->listeners[ $eventName ][] = $listener;
    }

    /**
     * Dispatch an event payload to registered listeners and WordPress action hooks.
     *
     * @param string $eventName
     * @param mixed  ...$payload
     * @return void
     */
    public function dispatch(string $eventName, mixed ...$payload): void
    {
        if (isset($this->listeners[ $eventName ])) {
            foreach ($this->listeners[ $eventName ] as $listener) {
                $listener(...$payload);
            }
        }

        if (function_exists('do_action')) {
            $wpHookName = 'wp_ai_os_' . str_replace('.', '_', $eventName);
            do_action($wpHookName, ...$payload);
        }
    }

    /**
     * Filter a value through listeners and WordPress filter hooks.
     *
     * @param string $filterName
     * @param mixed  $value
     * @param mixed  ...$args
     * @return mixed
     */
    public function filter(string $filterName, mixed $value, mixed ...$args): mixed
    {
        $current = $value;

        if (isset($this->listeners[ $filterName ])) {
            foreach ($this->listeners[ $filterName ] as $listener) {
                $current = $listener($current, ...$args);
            }
        }

        if (function_exists('apply_filters')) {
            $wpFilterName = 'wp_ai_os_' . str_replace('.', '_', $filterName);
            $current      = apply_filters($wpFilterName, $current, ...$args);
        }

        return $current;
    }
}
