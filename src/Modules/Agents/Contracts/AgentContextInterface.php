<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentContextInterface
 */
interface AgentContextInterface
{
    public function getTaskId(): string;

    public function getUserId(): ?int;

    public function getPermissions(): array;

    public function getSessionData(): array;
}
