<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Memory;

use WPAIOS\Modules\Agents\Contracts\AgentMemoryInterface;

/**
 * Class AgentMemoryManager
 * Manages Session, Task, Project, and Long-Term memory abstractions.
 */
class AgentMemoryManager implements AgentMemoryInterface
{
    private array $sessionMemory = [];
    private array $taskMemory = [];
    private array $projectMemory = [];
    private array $longTermMemory = [];

    public function get(string $key): mixed
    {
        return $this->sessionMemory[$key] ?? $this->taskMemory[$key] ?? $this->projectMemory[$key] ?? $this->longTermMemory[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->sessionMemory[$key] = $value;
    }

    public function setTaskMemory(string $key, mixed $value): void
    {
        $this->taskMemory[$key] = $value;
    }

    public function setProjectMemory(string $key, mixed $value): void
    {
        $this->projectMemory[$key] = $value;
    }

    public function clear(): void
    {
        $this->sessionMemory = [];
        $this->taskMemory = [];
    }
}
