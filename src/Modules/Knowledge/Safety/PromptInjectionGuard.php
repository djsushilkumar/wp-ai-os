<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Safety;

use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;

/**
 * Class PromptInjectionGuard
 * Defense in depth against prompt injection in retrieved knowledge base content.
 */
class PromptInjectionGuard
{
    private array $maliciousPatterns = [
        '/ignore\s+(all\s+)?previous\s+instructions/i',
        '/you\s+are\s+now\s+unrestricted/i',
        '/bypass\s+security\s+policy/i',
        '/override\s+system\s+prompt/i',
        '/eval\s*\(/i',
        '/system\s*\(/i',
    ];

    /**
     * Clean and wrap untrusted retrieved chunk content with clear context boundaries.
     */
    public function sanitizeChunk(KnowledgeChunkModel $chunk): KnowledgeChunkModel
    {
        $content = $chunk->getContent();

        // Check for prompt injection attempts
        foreach ($this->maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $content = '[WARNING: Malicious instruction pattern stripped from retrieved knowledge chunk]';
                break;
            }
        }

        return new KnowledgeChunkModel(
            $chunk->getId(),
            $chunk->getSourceId(),
            $chunk->getSourceType(),
            $chunk->getSourceTitle(),
            $content,
            $chunk->getMetadata(),
            $chunk->getSourceUrl(),
            $chunk->getWpObjectId(),
            $chunk->getRelevanceScore()
        );
    }
}
