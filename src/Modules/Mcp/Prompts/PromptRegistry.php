<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Prompts;

use Exception;

/**
 * Prompt Registry managing reusable system prompts across categories.
 */
class PromptRegistry
{
    /**
     * @var array<string, PromptInterface>
     */
    private array $prompts = [];

    public function register(PromptInterface $prompt): void
    {
        $this->prompts[$prompt->id()] = $prompt;
    }

    public function get(string $id): PromptInterface
    {
        if (!isset($this->prompts[$id])) {
            throw new Exception(sprintf('Prompt [%s] is not registered.', $id));
        }

        return $this->prompts[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->prompts[$id]);
    }

    /**
     * Get prompts by category ('Elementor', 'SEO', 'WooCommerce', 'Blog', 'Media', 'Automation', 'Developer').
     *
     * @param string $category
     * @return PromptInterface[]
     */
    public function getByCategory(string $category): array
    {
        $filtered = [];
        foreach ($this->prompts as $prompt) {
            if (strtolower($prompt->category()) === strtolower($category)) {
                $filtered[] = $prompt;
            }
        }
        return $filtered;
    }

    /**
     * Format prompts list for MCP prompts/list JSON-RPC response.
     *
     * @return array<array{name: string, description: string, arguments: array<mixed>}>
     */
    public function toMcpList(): array
    {
        $list = [];
        foreach ($this->prompts as $prompt) {
            $list[] = [
                'name' => $prompt->id(),
                'description' => $prompt->description(),
                'arguments' => $prompt->arguments(),
            ];
        }
        return $list;
    }

    /**
     * @return array<string, PromptInterface>
     */
    public function all(): array
    {
        return $this->prompts;
    }
}
