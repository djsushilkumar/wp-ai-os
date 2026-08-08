<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Chunking;

use WPAIOS\Modules\Knowledge\Models\KnowledgeChunkModel;

/**
 * Class TextChunker
 * Configurable sentence and paragraph boundary text chunker.
 */
class TextChunker
{
    public function __construct(
        private int $chunkSize = 500,
        private int $chunkOverlap = 50
    ) {
    }

    /**
     * @return KnowledgeChunkModel[]
     */
    public function chunk(string $text, string $sourceId, string $sourceType, string $sourceTitle, array $metadata = []): array
    {
        if (empty(trim($text))) {
            return [];
        }

        $paragraphs = preg_split('/\n\s*\n/', $text);
        $chunks = [];
        $currentChunk = '';
        $chunkIndex = 0;

        foreach ($paragraphs as $para) {
            $para = trim($para);
            if (empty($para)) {
                continue;
            }

            if (strlen($currentChunk . ' ' . $para) > $this->chunkSize && !empty($currentChunk)) {
                $id = sprintf('%s_chunk_%d', $sourceId, $chunkIndex++);
                $chunks[] = new KnowledgeChunkModel(
                    $id,
                    $sourceId,
                    $sourceType,
                    $sourceTitle,
                    trim($currentChunk),
                    array_merge($metadata, ['chunk_index' => $chunkIndex])
                );
                // Overlap handling
                $currentChunk = substr($currentChunk, -$this->chunkOverlap) . ' ' . $para;
            } else {
                $currentChunk .= (empty($currentChunk) ? '' : "\n\n") . $para;
            }
        }

        if (!empty(trim($currentChunk))) {
            $id = sprintf('%s_chunk_%d', $sourceId, $chunkIndex);
            $chunks[] = new KnowledgeChunkModel(
                $id,
                $sourceId,
                $sourceType,
                $sourceTitle,
                trim($currentChunk),
                array_merge($metadata, ['chunk_index' => $chunkIndex])
            );
        }

        return $chunks;
    }
}
