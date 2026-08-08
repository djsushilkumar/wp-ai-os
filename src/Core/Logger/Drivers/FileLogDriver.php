<?php

declare(strict_types=1);

namespace WPAIOS\Core\Logger\Drivers;

/**
 * File Log Driver writing logs to a dedicated log file.
 */
class FileLogDriver implements LogDriverInterface
{
    /**
     * @param string $logFilePath Target file path for logs.
     */
    public function __construct(private string $logFilePath)
    {
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $formatted = sprintf(
            '[%s] [%s] %s %s',
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            ! empty($context) ? json_encode($context) : ''
        );

        $dir = dirname($this->logFilePath);
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        @file_put_contents($this->logFilePath, $formatted . PHP_EOL, FILE_APPEND);
    }
}
