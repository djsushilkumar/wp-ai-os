<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Contracts;

use WPAIOS\Modules\AI\Models\Message;

interface MemoryInterface
{
    /**
     * Add message to conversation memory.
     *
     * @param Message $message
     * @return void
     */
    public function addMessage(Message $message): void;

    /**
     * Get all memory messages.
     *
     * @return Message[]
     */
    public function getMessages(): array;

    /**
     * Clear memory context.
     *
     * @return void
     */
    public function clear(): void;
}
