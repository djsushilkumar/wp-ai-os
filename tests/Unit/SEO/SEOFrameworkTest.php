<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\SEO;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\SEO\Models\SEOMetadataModel;
use WPAIOS\Modules\SEO\Services\SchemaBuilder;
use WPAIOS\Modules\SEO\Services\SEOAnalyzer;

class SEOFrameworkTest extends TestCase
{
    public function testSEOMetadataModelSerialization(): void
    {
        $meta = new SEOMetadataModel(
            postId: 42,
            metaTitle: 'Sample SEO Title - Enterprise WP AI OS',
            metaDescription: 'This is a sample meta description for testing the SEO framework.',
            focusKeyword: 'SEO Framework'
        );

        $array = $meta->toArray();

        $this->assertEquals(42, $array['post_id']);
        $this->assertEquals('Sample SEO Title - Enterprise WP AI OS', $array['meta_title']);
        $this->assertEquals('SEO Framework', $array['focus_keyword']);
    }

    public function testSEOAnalyzerScoresMetadata(): void
    {
        $analyzer = new SEOAnalyzer();
        $meta = new SEOMetadataModel(
            postId: 1,
            metaTitle: 'Perfect Meta Title for Testing SEO Optimization',
            metaDescription: 'This is a complete and descriptive meta description that satisfies length rules for search engine snippets.',
            focusKeyword: 'Testing'
        );

        $analysis = $analyzer->analyze($meta);

        $this->assertIsInt($analysis['score']);
        $this->assertGreaterThanOrEqual(80, $analysis['score']);
    }

    public function testSchemaBuilderBuildsOrganization(): void
    {
        $builder = new SchemaBuilder();
        $schema = $builder->buildOrganization('WP AI OS', 'https://wp-ai-os.io', 'https://wp-ai-os.io/logo.png');

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Organization', $schema['@type']);
        $this->assertEquals('WP AI OS', $schema['name']);
        $this->assertEquals('https://wp-ai-os.io/logo.png', $schema['logo']);
    }

    public function testSchemaBuilderBuildsFAQ(): void
    {
        $builder = new SchemaBuilder();
        $schema = $builder->buildFAQ([
            ['question' => 'What is WP AI OS?', 'answer' => 'An enterprise AI OS for WordPress.'],
        ]);

        $this->assertEquals('FAQPage', $schema['@type']);
        $this->assertCount(1, $schema['mainEntity']);
        $this->assertEquals('Question', $schema['mainEntity'][0]['@type']);
    }
}
