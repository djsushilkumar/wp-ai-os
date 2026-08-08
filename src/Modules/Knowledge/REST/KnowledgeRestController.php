<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\REST;

use WPAIOS\Modules\Knowledge\KnowledgeManager;

/**
 * Class KnowledgeRestController
 * REST API Endpoint `/wp-json/wp-ai-os/v1/knowledge`.
 */
class KnowledgeRestController
{
    private string $namespace = 'wp-ai-os/v1';

    public function __construct(private KnowledgeManager $manager)
    {
    }

    public function registerRoutes(): void
    {
        if (!function_exists('register_rest_route')) {
            return;
        }

        register_rest_route($this->namespace, '/knowledge/search', [
            [
                'methods' => 'POST',
                'callback' => [$this, 'searchKnowledge'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/knowledge/index/status', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getIndexStatus'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);
    }

    public function checkPermission(): bool
    {
        return function_exists('current_user_can') && current_user_can('manage_options');
    }

    public function searchKnowledge(\WP_REST_Request $request): \WP_REST_Response
    {
        $params = $request->get_json_params() ?? [];
        $query = $params['query'] ?? '';
        $topK = $params['top_k'] ?? 5;

        if (empty($query)) {
            return new \WP_REST_Response(['error' => 'Search query is required'], 400);
        }

        $chunks = $this->manager->getRetriever()->search($query, (int) $topK);
        $context = $this->manager->getContextBuilder()->buildContext($chunks);

        return new \WP_REST_Response($context, 200);
    }

    public function getIndexStatus(\WP_REST_Request $request): \WP_REST_Response
    {
        return new \WP_REST_Response([
            'status' => 'active',
            'embedding_driver' => 'EmbeddingService (OpenAI / Gemini / Local Abstraction)',
            'vector_store' => 'MySQLVectorStore (wp_ai_os_vectors)',
        ], 200);
    }
}
