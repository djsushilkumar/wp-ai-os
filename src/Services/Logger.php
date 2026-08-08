<?php

declare(strict_types=1);

namespace WPAIOS\Services;

use WPAIOS\Contracts\LoggerInterface;

/**
 * PSR-3 Compliant Logging Service for WP AI OS.
 */
class Logger implements LoggerInterface
{
    /**
     * @param string $logFilePath Target path for log file.
     */
    public function __construct(private string $logFilePath = '')
    {
    }

    /**
     * System is unusable.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log('EMERGENCY', (string) $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log('ALERT', (string) $message, $context);
    }

    /**
     * Critical conditions.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log('CRITICAL', (string) $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should be logged.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log('ERROR', (string) $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log('WARNING', (string) $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log('NOTICE', (string) $message, $context);
    }

    /**
     * Interesting events.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log('INFO', (string) $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log('DEBUG', (string) $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $formatted = sprintf(
            '[%s] [%s] %s %s',
            date('Y-m-d H:i:s'),
            strtoupper((string) $level),
            (string) $message,
            !empty($context) ? json_encode($context) : ''
        );

        if (!empty($this->logFilePath)) {
            $dir = dirname($this->logFilePath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            @file_put_contents($this->logFilePath, $formatted . PHP_EOL, FILE_APPEND);
        }

        if (function_exists('error_log')) {
            error_log('[WP AI OS] ' . $formatted);
        }
    }
}
