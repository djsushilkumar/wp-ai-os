<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentPolicyInterface
 */
interface AgentPolicyInterface
{
    public function isAllowed(AgentInterface $agent, string $abilityName, array $params): bool;
}
