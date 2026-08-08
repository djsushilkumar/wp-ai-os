<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Context;

use WPAIOS\Modules\AI\Models\Message;

/**
 * Context Window Manager enforcing model max token limits by truncating oldest conversation messages.
 */
class ContextWindow
{
    public function __construct(private TokenCounter $counter)
    {
    }

    /**
     * Truncate messages to fit within max token limit.
     *
     * @param Message[] $messages
     * @param int $maxTokens Default 128000 tokens
     * @return Message[]
     */
    public function fit(array $messages, int $maxTokens = 128000): array
    {
        $totalTokens = 0;
        $fitted = [];

        // Retain system prompt if present
        $systemMsg = null;
        if (!empty($messages) && ($messages[0]->role === 'system' || $messages[0]->role === 'developer')) {
            $systemMsg = array_shift($messages);
            $totalTokens += $this->counter->count(is_string($systemMsg->content) ? $systemMsg->content : '');
        }

        // Iterate backwards from newest to oldest
        $reversed = array_reverse($messages);
        foreach ($reversed as $msg) {
            $msgTokens = $this->counter->count(is_string($msg->content) ? $msg->content : '');
            if ($totalTokens + $msgTokens > $maxTokens) {
                break;
            }
            array_unshift($fitted, $msg);
            $totalTokens += $msgTokens;
        }

        if ($systemMsg) {
            array_unshift($fitted, $systemMsg);
        }

        return $fitted;
    }
}
