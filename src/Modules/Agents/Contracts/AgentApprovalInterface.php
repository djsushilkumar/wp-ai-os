<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Contracts;

/**
 * Interface AgentApprovalInterface
 */
interface AgentApprovalInterface
{
    public function getId(): string;

    public function getTaskId(): string;

    public function getStatus(): string;

    public function getRiskLevel(): string;
}
