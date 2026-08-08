<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * Logger Interface extending PSR-3 LoggerInterface.
 */
interface LoggerInterface extends PsrLoggerInterface
{
    /**
     * Log a debug trace message.
     *
     * @param string               $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void;

    /**
     * Log an informational message.
     *
     * @param string               $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void;

    /**
     * Log a warning message.
     *
     * @param string               $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void;

    /**
     * Log an error message.
     *
     * @param string               $message
     * @param array<string, mixed> $context
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void;
}
