<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Profiles;

use WPAIOS\Modules\Agents\Contracts\AgentProfileInterface;

/**
 * Class AgentProfile
 * Value object declaring agent metadata, risk level, allowed abilities, and execution caps.
 */
class AgentProfile implements AgentProfileInterface
{
    public function __construct(
        private string $id,
        private string $name,
        private string $description,
        private string $version,
        private string $role,
        private string $riskLevel,
        private array $allowedAbilities = [],
        private int $maxExecutionTime = 300,
        private int $maxTaskCount = 50
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getRiskLevel(): string
    {
        return $this->riskLevel;
    }

    public function getAllowedAbilities(): array
    {
        return $this->allowedAbilities;
    }

    public function getMaxExecutionTime(): int
    {
        return $this->maxExecutionTime;
    }

    public function getMaxTaskCount(): int
    {
        return $this->maxTaskCount;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'version' => $this->version,
            'role' => $this->role,
            'risk_level' => $this->riskLevel,
            'allowed_abilities' => $this->allowedAbilities,
            'max_execution_time' => $this->maxExecutionTime,
            'max_task_count' => $this->maxTaskCount,
        ];
    }
}
