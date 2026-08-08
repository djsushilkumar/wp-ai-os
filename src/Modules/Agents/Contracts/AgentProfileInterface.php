<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentProfileInterface
 */
interface AgentProfileInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getRiskLevel(): string;

    public function getAllowedAbilities(): array;

    public function getMaxExecutionTime(): int;
}
