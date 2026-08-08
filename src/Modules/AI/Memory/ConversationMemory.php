<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Memory;

use WPAIOS\Modules\AI\Contracts\MemoryInterface;
use WPAIOS\Modules\AI\Models\Message;

/**
 * In-memory Conversation Memory store.
 */
class ConversationMemory implements MemoryInterface
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function clear(): void
    {
        $this->messages = [];
    }
}
