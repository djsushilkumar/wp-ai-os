<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Prompts;

/**
 * MCP Prompt Template Interface contract.
 */
interface PromptInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;

    /**
     * Category string ('Elementor', 'SEO', 'WooCommerce', 'Blog', 'Media', 'Automation', 'Developer').
     *
     * @return string
     */
    public function category(): string;

    /**
     * @return array<array{name: string, description: string, required: bool}>
     */
    public function arguments(): array;

    /**
     * Compile prompt template with arguments.
     *
     * @param array<string, mixed> $args
     * @return array<array{role: string, content: string}>
     */
    public function render(array $args = []): array;
}
