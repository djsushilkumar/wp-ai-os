<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Array manipulation helper utility.
 */
class Arr
{
    /**
     * Get item from array using dot notation.
     *
     * @param array<string, mixed> $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(array $array, string $key, mixed $default = null): mixed
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set item in array using dot notation.
     *
     * @param array<string, mixed> $array
     * @param string $key
     * @param mixed $value
     * @return array<string, mixed>
     */
    public function set(array &$array, string $key, mixed $value): array
    {
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $k = array_shift($keys);
            if (!isset($array[$k]) || !is_array($array[$k])) {
                $array[$k] = [];
            }
            $array = &$array[$k];
        }

        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * Pluck array values by key.
     *
     * @param array<array<string, mixed>> $array
     * @param string $valueKey
     * @return array<mixed>
     */
    public function pluck(array $array, string $valueKey): array
    {
        $results = [];
        foreach ($array as $item) {
            if (is_array($item) && isset($item[$valueKey])) {
                $results[] = $item[$valueKey];
            }
        }
        return $results;
    }
}
