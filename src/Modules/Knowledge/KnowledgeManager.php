<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge;

use WPAIOS\Modules\Knowledge\Chunking\TextChunker;
use WPAIOS\Modules\Knowledge\Context\ContextBuilder;
use WPAIOS\Modules\Knowledge\Embeddings\EmbeddingService;
use WPAIOS\Modules\Knowledge\Ingestion\IngestionPipeline;
use WPAIOS\Modules\Knowledge\Permissions\PermissionFilter;
use WPAIOS\Modules\Knowledge\Retrieval\HybridRetriever;
use WPAIOS\Modules\Knowledge\Vector\MySQLVectorStore;

/**
 * Class KnowledgeManager
 * Central facade for Knowledge Base & RAG Platform.
 */
class KnowledgeManager
{
    public function __construct(
        private TextChunker $chunker,
        private EmbeddingService $embeddingService,
        private MySQLVectorStore $vectorStore,
        private IngestionPipeline $pipeline,
        private HybridRetriever $retriever,
        private ContextBuilder $contextBuilder,
        private PermissionFilter $permissionFilter
    ) {
    }

    public function getPipeline(): IngestionPipeline
    {
        return $this->pipeline;
    }

    public function getRetriever(): HybridRetriever
    {
        return $this->retriever;
    }

    public function getContextBuilder(): ContextBuilder
    {
        return $this->contextBuilder;
    }

    public function getPermissionFilter(): PermissionFilter
    {
        return $this->permissionFilter;
    }

    public function getVectorStore(): MySQLVectorStore
    {
        return $this->vectorStore;
    }
}
