<?php

declare(strict_types=1);

namespace WPAIOS\Services;

use WPAIOS\Contracts\ConfigInterface;

/**
 * Enterprise Configuration System supporting environment detection, dot-notation, and caching.
 */
class ConfigLoader implements ConfigInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $items = [];

    private string $environment = 'production';

    /**
     * @param string $configDir Directory path containing .php config files.
     * @param string|null $env Environment override string ('production', 'staging', 'development', 'testing').
     */
    public function __construct(private string $configDir, ?string $env = null)
    {
        $this->environment = $env ?? $this->detectEnvironment();
        $this->loadConfigFiles();
    }

    /**
     * Detect running environment via WP_ENVIRONMENT_TYPE or constant.
     *
     * @return string
     */
    public function detectEnvironment(): string
    {
        if (function_exists('wp_get_environment_type')) {
            return wp_get_environment_type();
        }

        if (defined('WP_ENVIRONMENT_TYPE')) {
            return (string) WP_ENVIRONMENT_TYPE;
        }

        return 'production';
    }

    /**
     * Get current detected environment string.
     *
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Check if currently running in a specific environment.
     *
     * @param string $env
     * @return bool
     */
    public function isEnvironment(string $env): bool
    {
        return strtolower($this->environment) === strtolower($env);
    }

    /**
     * Load configuration files.
     *
     * @return void
     */
    private function loadConfigFiles(): void
    {
        if (!is_dir($this->configDir)) {
            return;
        }

        $files = glob($this->configDir . '/*.php');
        if (false === $files) {
            return;
        }

        foreach ($files as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $data = include $file;

            if (is_array($data)) {
                $this->items[$key] = $data;
            }
        }
    }

    /**
     * Get a configuration value using dot notation (e.g., 'app.version', 'modules.enabled').
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (empty($key)) {
            return $this->items;
        }

        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        $array = $this->items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set a configuration value at runtime.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $array = &$this->items;

        while (count($keys) > 1) {
            $k = array_shift($keys);
            if (!isset($array[$k]) || !is_array($array[$k])) {
                $array[$k] = [];
            }
            $array = &$array[$k];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return null !== $this->get($key);
    }

    /**
     * Get all loaded configuration arrays.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->items;
    }
}
