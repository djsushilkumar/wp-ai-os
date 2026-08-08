<?php

declare(strict_types=1);

namespace WPAIOS\Core\Logger\Drivers;

/**
 * WordPress Error Log Driver sending log entries to PHP system error_log.
 */
class WpLogDriver implements LogDriverInterface
{
    public function log(string $level, string $message, array $context = []): void
    {
        if (function_exists('error_log')) {
            $formatted = sprintf(
                '[WP AI OS] [%s] %s %s',
                strtoupper($level),
                $message,
                ! empty($context) ? json_encode($context) : ''
            );
            error_log($formatted);
        }
    }
}
