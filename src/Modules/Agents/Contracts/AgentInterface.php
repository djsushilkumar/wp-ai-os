<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentInterface
 */
interface AgentInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getRole(): string;

    public function getRiskLevel(): string;

    public function executeTask(AgentTaskInterface $task, AgentContextInterface $context): array;
}
