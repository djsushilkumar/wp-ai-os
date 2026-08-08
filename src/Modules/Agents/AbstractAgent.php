<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents;

use WPAIOS\Modules\Agents\Contracts\AgentContextInterface;
use WPAIOS\Modules\Agents\Contracts\AgentInterface;
use WPAIOS\Modules\Agents\Contracts\AgentTaskInterface;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

/**
 * Class AbstractAgent
 * Base abstract class for all specialized agents.
 */
abstract class AbstractAgent implements AgentInterface
{
    public function __construct(protected AgentProfile $profile)
    {
    }

    public function getId(): string
    {
        return $this->profile->getId();
    }

    public function getName(): string
    {
        return $this->profile->getName();
    }

    public function getDescription(): string
    {
        return $this->profile->getDescription();
    }

    public function getRole(): string
    {
        return $this->profile->getRole();
    }

    public function getRiskLevel(): string
    {
        return $this->profile->getRiskLevel();
    }

    public function getProfile(): AgentProfile
    {
        return $this->profile;
    }

    public function executeTask(AgentTaskInterface $task, AgentContextInterface $context): array
    {
        return [
            'status'   => 'completed',
            'agent_id' => $this->getId(),
            'task_id'  => $task->getId(),
            'output'   => sprintf('Agent %s successfully executed task: %s', $this->getName(), $task->getGoal()),
        ];
    }
}
