<?php

declare(strict_types=1);

namespace WPAIOS\Contracts;

/**
 * Event Listener Interface contract.
 */
interface EventListenerInterface
{
    /**
     * Handle the event payload.
     *
     * @param mixed ...$payload
     * @return void
     */
    public function handle(mixed ...$payload): void;
}
