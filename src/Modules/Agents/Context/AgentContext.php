<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Context;

use WPAIOS\Modules\Agents\Contracts\AgentContextInterface;

/**
 * Class AgentContext
 * Isolated context protecting sensitive credentials and holding task execution state.
 */
class AgentContext implements AgentContextInterface
{
    public function __construct(
        private string $taskId,
        private ?int $userId = null,
        private array $permissions = [],
        private array $sessionData = []
    ) {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getSessionData(): array
    {
        return $this->sessionData;
    }
}
