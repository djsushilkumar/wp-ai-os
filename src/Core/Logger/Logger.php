<?php

declare(strict_types=1);

namespace WPAIOS\Core\Logger;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Core\Logger\Drivers\LogDriverInterface;

/**
 * PSR-3 Compliant Multi-Driver Logger for WP AI OS.
 */
class Logger implements LoggerInterface
{
    /**
     * @var LogDriverInterface[]
     */
    private array $drivers = [];

    /**
     * @param LogDriverInterface[] $drivers Initial driver list.
     */
    public function __construct(array $drivers = [])
    {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    /**
     * Add a log driver target.
     *
     * @param LogDriverInterface $driver
     * @return void
     */
    public function addDriver(LogDriverInterface $driver): void
    {
        $this->drivers[] = $driver;
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $levelString = (string) $level;
        $msgString   = (string) $message;

        foreach ($this->drivers as $driver) {
            $driver->log($levelString, $msgString, $context);
        }
    }
}
