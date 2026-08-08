<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentMemoryInterface
 */
interface AgentMemoryInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    public function clear(): void;
}
