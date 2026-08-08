<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Models;

/**
 * Workflow Status Enum-like constants.
 */
class WorkflowStatus
{
    public const PENDING   = 'pending';
    public const PLANNING  = 'planning';
    public const RUNNING   = 'running';
    public const PAUSED    = 'paused';
    public const COMPLETED = 'completed';
    public const FAILED    = 'failed';
    public const CANCELLED = 'cancelled';
    public const ROLLING_BACK = 'rolling_back';
    public const ROLLED_BACK  = 'rolled_back';
    public const RECOVERING   = 'recovering';
}
