<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\SEO\Adapters\FallbackSEOAdapter;
use WPAIOS\Modules\SEO\Models\SEOMetadataModel;
use WPAIOS\Modules\SEO\Services\SEOAnalyzer;

/**
 * SEO Metadata Ability — allows MCP agents to read, update, and analyze post SEO metadata.
 */
class SEOMetadataAbility extends AbstractAbility
{
    protected string $category = 'SEO';
    protected array $permissions = ['edit_posts'];

    public function __construct(
        private FallbackSEOAdapter $adapter,
        private SEOAnalyzer $analyzer
    ) {
    }

    public function id(): string
    {
        return 'wp_ai_os_seo_metadata';
    }

    public function name(): string
    {
        return 'SEO Metadata Manager & Analyzer';
    }

    public function description(): string
    {
        return 'Read, update, and evaluate health scores for WordPress SEO meta titles, meta descriptions, focus keywords, and canonical URLs.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['get', 'update', 'analyze']],
                'post_id' => ['type' => 'integer'],
                'meta_title' => ['type' => 'string'],
                'meta_description' => ['type' => 'string'],
                'focus_keyword' => ['type' => 'string'],
                'canonical_url' => ['type' => 'string'],
            ],
            'required' => ['action', 'post_id'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $postId = (int) ($params['post_id'] ?? 0);

        if ($action === 'get') {
            $meta = $this->adapter->getMetadata($postId);
            return $meta?->toArray();
        }

        if ($action === 'update') {
            $current = $this->adapter->getMetadata($postId);
            $newMeta = new SEOMetadataModel(
                postId: $postId,
                metaTitle: $params['meta_title'] ?? ($current->metaTitle ?? ''),
                metaDescription: $params['meta_description'] ?? ($current->metaDescription ?? ''),
                focusKeyword: $params['focus_keyword'] ?? ($current->focusKeyword ?? ''),
                canonicalUrl: $params['canonical_url'] ?? ($current->canonicalUrl ?? '')
            );

            $success = $this->adapter->updateMetadata($postId, $newMeta);
            return ['success' => $success, 'metadata' => $newMeta->toArray()];
        }

        if ($action === 'analyze') {
            $meta = $this->adapter->getMetadata($postId);
            if (!$meta) {
                throw new Exception(sprintf('Post ID %d not found.', $postId));
            }
            return $this->analyzer->analyze($meta);
        }

        throw new Exception("Unknown SEO action: {$action}");
    }
}
