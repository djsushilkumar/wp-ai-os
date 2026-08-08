<?php

declare(strict_types=1);

namespace WPAIOS\Core\Event;

/**
 * Value Object holding listener callback and priority metadata.
 */
class ListenerRegistration
{
    /**
     * @param callable $listener
     * @param int      $priority Lower numbers execute earlier (e.g. 10 is default).
     */
    public function __construct(
        public readonly mixed $listener,
        public readonly int $priority = 10
    ) {
    }
}
