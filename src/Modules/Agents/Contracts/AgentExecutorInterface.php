<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentExecutorInterface
 */
interface AgentExecutorInterface
{
    public function execute(string $abilityName, array $params, AgentContextInterface $context): array;
}
