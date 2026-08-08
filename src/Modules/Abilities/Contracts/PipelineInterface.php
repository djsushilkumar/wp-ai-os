<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Contracts;

/**
 * Pipeline Interface contract for multi-ability execution chains.
 */
interface PipelineInterface
{
    /**
     * Send initial payload through pipeline of abilities.
     *
     * @param mixed         $passable
     * @param array<string> $abilityIds
     * @return mixed
     */
    public function sendThrough(mixed $passable, array $abilityIds): mixed;
}
