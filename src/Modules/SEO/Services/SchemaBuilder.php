<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Services;

/**
 * Schema.org JSON-LD Generator supporting Article, BlogPosting, Product, Organization, Person, LocalBusiness, FAQ, and HowTo schemas.
 */
class SchemaBuilder
{
    /**
     * Build Organization Schema.org payload.
     *
     * @param string $name
     * @param string $url
     * @param string $logoUrl
     * @return array<string, mixed>
     */
    public function buildOrganization(string $name, string $url, string $logoUrl = ''): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
        ];

        if (!empty($logoUrl)) {
            $schema['logo'] = $logoUrl;
        }

        return $schema;
    }

    /**
     * Build Article / BlogPosting Schema.org payload.
     *
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $authorName
     * @param string $datePublished
     * @param string $type 'Article' or 'BlogPosting'
     * @return array<string, mixed>
     */
    public function buildArticle(
        string $title,
        string $description,
        string $url,
        string $authorName,
        string $datePublished,
        string $type = 'Article'
    ): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'headline' => $title,
            'description' => $description,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
            'author' => ['@type' => 'Person', 'name' => $authorName],
            'datePublished' => $datePublished,
        ];
    }

    /**
     * Build FAQPage Schema.org payload.
     *
     * @param array<array{question: string, answer: string}> $qaPairs
     * @return array<string, mixed>
     */
    public function buildFAQ(array $qaPairs): array
    {
        $mainEntity = [];
        foreach ($qaPairs as $pair) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $pair['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $pair['answer'] ?? '',
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }
}
