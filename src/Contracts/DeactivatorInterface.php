<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Deactivator Interface contract for plugin deactivation lifecycle routines.
 */
interface DeactivatorInterface
{
    /**
     * Run plugin deactivation tasks (cron cleanup, rewrite flushing, temporary file removal).
     *
     * @return void
     */
    public function deactivate(): void;
}
