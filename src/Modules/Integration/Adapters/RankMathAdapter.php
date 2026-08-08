<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Adapters;

/**
 * Rank Math SEO Adapter.
 */
class RankMathAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'rankmath';
    }

    public function name(): string
    {
        return 'Rank Math SEO';
    }

    public function detect(): bool
    {
        return class_exists('RankMath') || defined('RANK_MATH_VERSION');
    }
}
