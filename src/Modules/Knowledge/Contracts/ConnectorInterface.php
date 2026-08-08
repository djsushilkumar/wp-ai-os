<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

/**
 * Interface ConnectorInterface
 */
interface ConnectorInterface
{
    public function connect(): bool;

    public function disconnect(): bool;

    public function health(): bool;

    public function fetch(): array;
}
