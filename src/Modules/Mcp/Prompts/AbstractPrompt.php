<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Prompts;

/**
 * Abstract Prompt Template base class supporting categories (Elementor, SEO, WooCommerce, Blog, Media, Automation, Developer).
 */
abstract class AbstractPrompt implements PromptInterface
{
    protected string $category = 'Developer';

    public function category(): string
    {
        return $this->category;
    }

    public function arguments(): array
    {
        return [];
    }
}
