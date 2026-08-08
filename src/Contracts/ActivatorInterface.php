<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Activator Interface contract for plugin activation lifecycle routines.
 */
interface ActivatorInterface
{
    /**
     * Run plugin activation tasks (DB migration, option creation, rewrite flushing).
     *
     * @return void
     */
    public function activate(): void;
}
