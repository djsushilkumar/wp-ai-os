<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentTaskInterface
 */
interface AgentTaskInterface
{
    public function getId(): string;

    public function getGoal(): string;

    public function getStatus(): string;

    public function getInputs(): array;
}
