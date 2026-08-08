<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentObserverInterface
 */
interface AgentObserverInterface
{
    public function onAgentEvent(string $event, array $payload): void;
}
