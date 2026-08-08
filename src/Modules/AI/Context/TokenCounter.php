<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Context;

/**
 * Token Counter estimating token counts for text strings and message payloads.
 */
class TokenCounter
{
    /**
     * Estimate token count for a text string (~4 characters per token).
     *
     * @param string $text
     * @return int
     */
    public function count(string $text): int
    {
        if (empty(trim($text))) {
            return 0;
        }

        return (int) ceil(mb_strlen($text) / 4);
    }
}
