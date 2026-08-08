<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Providers;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Knowledge\Chunking\TextChunker;
use WPAIOS\Modules\Knowledge\Context\ContextBuilder;
use WPAIOS\Modules\Knowledge\Embeddings\EmbeddingService;
use WPAIOS\Modules\Knowledge\Ingestion\IngestionPipeline;
use WPAIOS\Modules\Knowledge\KnowledgeManager;
use WPAIOS\Modules\Knowledge\Permissions\PermissionFilter;
use WPAIOS\Modules\Knowledge\Retrieval\HybridRetriever;
use WPAIOS\Modules\Knowledge\Safety\PromptInjectionGuard;
use WPAIOS\Modules\Knowledge\Vector\MySQLVectorStore;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Class KnowledgeServiceProvider
 * Registers Knowledge Base & RAG Platform dependencies into DI container.
 */
class KnowledgeServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(TextChunker::class, fn () => new TextChunker());
        $this->container->singleton(EmbeddingService::class, fn () => new EmbeddingService());
        $this->container->singleton(MySQLVectorStore::class, fn () => new MySQLVectorStore());
        $this->container->singleton(PromptInjectionGuard::class, fn () => new PromptInjectionGuard());
        $this->container->singleton(PermissionFilter::class, fn () => new PermissionFilter());

        $this->container->singleton(IngestionPipeline::class, function (Container $c) {
            return new IngestionPipeline(
                $c->get(TextChunker::class),
                $c->get(EmbeddingService::class),
                $c->get(MySQLVectorStore::class)
            );
        });

        $this->container->singleton(HybridRetriever::class, function (Container $c) {
            return new HybridRetriever(
                $c->get(EmbeddingService::class),
                $c->get(MySQLVectorStore::class)
            );
        });

        $this->container->singleton(ContextBuilder::class, function (Container $c) {
            return new ContextBuilder($c->get(PromptInjectionGuard::class));
        });

        $this->container->singleton(KnowledgeManager::class, function (Container $c) {
            return new KnowledgeManager(
                $c->get(TextChunker::class),
                $c->get(EmbeddingService::class),
                $c->get(MySQLVectorStore::class),
                $c->get(IngestionPipeline::class),
                $c->get(HybridRetriever::class),
                $c->get(ContextBuilder::class),
                $c->get(PermissionFilter::class)
            );
        });
    }

    public function boot(): void
    {
    }
}
