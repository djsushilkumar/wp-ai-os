<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Models;

/**
 * Normalized SEO Metadata Value Object.
 */
class SEOMetadataModel
{
    /**
     * @param int $postId
     * @param string $metaTitle
     * @param string $metaDescription
     * @param string $focusKeyword
     * @param string $canonicalUrl
     * @param string $ogTitle
     * @param string $ogDescription
     * @param string $ogImage
     * @param string $twitterTitle
     * @param string $twitterDescription
     * @param string $robotsMeta 'index, follow', 'noindex, follow', etc.
     */
    public function __construct(
        public readonly int $postId,
        public readonly string $metaTitle = '',
        public readonly string $metaDescription = '',
        public readonly string $focusKeyword = '',
        public readonly string $canonicalUrl = '',
        public readonly string $ogTitle = '',
        public readonly string $ogDescription = '',
        public readonly string $ogImage = '',
        public readonly string $twitterTitle = '',
        public readonly string $twitterDescription = '',
        public readonly string $robotsMeta = 'index, follow'
    ) {
    }

    public function toArray(): array
    {
        return [
            'post_id' => $this->postId,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'focus_keyword' => $this->focusKeyword,
            'canonical_url' => $this->canonicalUrl,
            'og_title' => $this->ogTitle,
            'og_description' => $this->ogDescription,
            'og_image' => $this->ogImage,
            'twitter_title' => $this->twitterTitle,
            'twitter_description' => $this->twitterDescription,
            'robots_meta' => $this->robotsMeta,
        ];
    }
}
