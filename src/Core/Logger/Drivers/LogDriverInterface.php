<?php

declare(strict_types=1);

namespace WPAIOS\Core\Logger\Drivers;

/**
 * Log Driver Interface contract for logging targets.
 */
interface LogDriverInterface
{
    /**
     * Write log entry to target storage.
     *
     * @param string               $level
     * @param string               $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void;
}
