<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Registry;

use WPAIOS\Modules\Agents\Contracts\AgentInterface;

/**
 * Class AgentRegistry
 * Registry holding all registered built-in and dynamic agents.
 */
class AgentRegistry
{
    private array $agents = [];

    public function register(AgentInterface $agent): void
    {
        $this->agents[$agent->getId()] = $agent;
    }

    public function unregister(string $id): void
    {
        unset($this->agents[$id]);
    }

    public function get(string $id): ?AgentInterface
    {
        return $this->agents[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->agents[$id]);
    }

    /**
     * @return AgentInterface[]
     */
    public function all(): array
    {
        return $this->agents;
    }

    public function listSummary(): array
    {
        $summary = [];
        foreach ($this->agents as $id => $agent) {
            $summary[$id] = [
                'id' => $agent->getId(),
                'name' => $agent->getName(),
                'description' => $agent->getDescription(),
                'role' => $agent->getRole(),
                'risk_level' => $agent->getRiskLevel(),
            ];
        }
        return $summary;
    }
}
