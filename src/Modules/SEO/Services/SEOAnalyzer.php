<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Services;

use WPAIOS\Modules\SEO\Models\SEOMetadataModel;

/**
 * Passive SEO Analyzer evaluating post metadata health, title length, description length, and focus keyword density.
 */
class SEOAnalyzer
{
    /**
     * Analyze SEO health of a metadata model.
     *
     * @param SEOMetadataModel $meta
     * @return array{score: int, warnings: string[], recommendations: string[]}
     */
    public function analyze(SEOMetadataModel $meta): array
    {
        $score = 100;
        $warnings = [];
        $recommendations = [];

        // 1. Meta Title Length
        $titleLen = mb_strlen($meta->metaTitle);
        if ($titleLen === 0) {
            $score -= 30;
            $warnings[] = 'Meta title is missing.';
            $recommendations[] = 'Add a descriptive title between 40 and 60 characters.';
        } elseif ($titleLen < 30 || $titleLen > 65) {
            $score -= 10;
            $warnings[] = sprintf('Meta title length (%d chars) is outside optimal range (40-60 chars).', $titleLen);
        }

        // 2. Meta Description Length
        $descLen = mb_strlen($meta->metaDescription);
        if ($descLen === 0) {
            $score -= 30;
            $warnings[] = 'Meta description is missing.';
            $recommendations[] = 'Add a compelling meta description between 120 and 160 characters.';
        } elseif ($descLen < 70 || $descLen > 165) {
            $score -= 10;
            $warnings[] = sprintf('Meta description length (%d chars) is outside optimal range (120-160 chars).', $descLen);
        }

        // 3. Focus Keyword
        if (empty($meta->focusKeyword)) {
            $score -= 15;
            $warnings[] = 'No focus keyword specified.';
            $recommendations[] = 'Specify a primary focus keyword for search engine targeting.';
        } elseif (!empty($meta->metaTitle) && !str_contains(mb_strtolower($meta->metaTitle), mb_strtolower($meta->focusKeyword))) {
            $score -= 10;
            $warnings[] = 'Focus keyword is not present in meta title.';
        }

        return [
            'score' => max(0, $score),
            'warnings' => $warnings,
            'recommendations' => $recommendations,
        ];
    }
}
