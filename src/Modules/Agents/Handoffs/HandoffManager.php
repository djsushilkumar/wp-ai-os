<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Handoffs;

use WPAIOS\Modules\Agents\Contracts\AgentTaskInterface;
use WPAIOS\Modules\Agents\Safety\LoopProtector;

/**
 * Class HandoffManager
 * Manages handoffs between agents with loop protection.
 */
class HandoffManager
{
    private array $handoffLog = [];

    public function __construct(private LoopProtector $loopProtector)
    {
    }

    public function handoff(string $fromAgentId, string $toAgentId, AgentTaskInterface $task, array $contextData = []): array
    {
        $this->loopProtector->recordHandoff();

        $handoffRecord = [
            'id' => 'handoff_' . uniqid(),
            'from_agent' => $fromAgentId,
            'to_agent' => $toAgentId,
            'task_id' => $task->getId(),
            'task_goal' => $task->getGoal(),
            'context' => $contextData,
            'timestamp' => gmdate('Y-m-d H:i:s'),
        ];

        $this->handoffLog[] = $handoffRecord;
        return $handoffRecord;
    }

    public function getHandoffLog(): array
    {
        return $this->handoffLog;
    }
}
