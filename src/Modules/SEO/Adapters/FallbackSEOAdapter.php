<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Adapters;

use WPAIOS\Modules\SEO\Models\SEOMetadataModel;

/**
 * Fallback SEO Adapter using native WordPress post meta.
 */
class FallbackSEOAdapter extends AbstractSEOAdapter
{
    public function id(): string
    {
        return 'fallback_seo';
    }

    public function name(): string
    {
        return 'WP AI OS Native Fallback SEO Driver';
    }

    public function detect(): bool
    {
        return true; // Always available
    }

    public function getMetadata(int $postId): ?SEOMetadataModel
    {
        $post = get_post($postId);
        if (!$post) {
            return null;
        }

        $title = (string) get_post_meta($postId, '_wp_ai_os_seo_title', true) ?: $post->post_title;
        $desc = (string) get_post_meta($postId, '_wp_ai_os_seo_description', true) ?: wp_strip_all_tags($post->post_excerpt ?: $post->post_content);
        $kw = (string) get_post_meta($postId, '_wp_ai_os_seo_keyword', true);
        $canonical = (string) get_post_meta($postId, '_wp_ai_os_seo_canonical', true) ?: (function_exists('get_permalink') ? get_permalink($postId) : '');

        return new SEOMetadataModel(
            postId: $postId,
            metaTitle: $title,
            metaDescription: mb_substr($desc, 0, 160),
            focusKeyword: $kw,
            canonicalUrl: $canonical
        );
    }

    public function updateMetadata(int $postId, SEOMetadataModel $metadata): bool
    {
        update_post_meta($postId, '_wp_ai_os_seo_title', sanitize_text_field($metadata->metaTitle));
        update_post_meta($postId, '_wp_ai_os_seo_description', sanitize_text_field($metadata->metaDescription));
        update_post_meta($postId, '_wp_ai_os_seo_keyword', sanitize_text_field($metadata->focusKeyword));
        if (!empty($metadata->canonicalUrl)) {
            update_post_meta($postId, '_wp_ai_os_seo_canonical', esc_url_raw($metadata->canonicalUrl));
        }

        return true;
    }

    public function getSchema(int $postId): array
    {
        $raw = get_post_meta($postId, '_wp_ai_os_json_ld_schema', true);
        if (empty($raw)) {
            return [];
        }

        $decoded = json_decode((string) $raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function updateSchema(int $postId, array $schemaData): bool
    {
        update_post_meta($postId, '_wp_ai_os_json_ld_schema', wp_json_encode($schemaData));
        return true;
    }
}
