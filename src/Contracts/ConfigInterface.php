<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Config Loader Interface for accessing dot-notated configuration keys.
 */
interface ConfigInterface
{
    /**
     * Get a configuration value using dot notation (e.g., 'app.version', 'modules.enabled').
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a configuration value at runtime.
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * Check if a configuration key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all loaded configuration arrays.
     *
     * @return array<string, mixed>
     */
    public function all(): array;
}
