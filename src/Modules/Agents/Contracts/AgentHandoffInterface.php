<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentHandoffInterface
 */
interface AgentHandoffInterface
{
    public function getFromAgentId(): string;

    public function getToAgentId(): string;

    public function getTask(): AgentTaskInterface;
}
