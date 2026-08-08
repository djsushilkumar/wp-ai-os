<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentRoleInterface
 */
interface AgentRoleInterface
{
    public function getRoleName(): string;

    public function getPermissions(): array;
}
