<?php

declare(strict_types=1);

namespace WPAIOS\Core\Event;

/**
 * Bridge converting WP AI OS domain events into WordPress Action & Filter Hooks.
 */
class WpHooksBridge
{
    /**
     * Dispatch event to WordPress `do_action`.
     *
     * @param string $eventName
     * @param mixed  ...$payload
     * @return void
     */
    public function doAction(string $eventName, mixed ...$payload): void
    {
        if (function_exists('do_action')) {
            $wpHookName = 'wp_ai_os_' . str_replace('.', '_', $eventName);
            do_action($wpHookName, ...$payload);
        }
    }

    /**
     * Filter value via WordPress `apply_filters`.
     *
     * @param string $filterName
     * @param mixed  $value
     * @param mixed  ...$args
     * @return mixed
     */
    public function applyFilters(string $filterName, mixed $value, mixed ...$args): mixed
    {
        if (function_exists('apply_filters')) {
            $wpFilterName = 'wp_ai_os_' . str_replace('.', '_', $filterName);
            return apply_filters($wpFilterName, $value, ...$args);
        }

        return $value;
    }
}
