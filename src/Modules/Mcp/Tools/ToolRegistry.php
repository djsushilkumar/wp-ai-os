<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Tools;

use Exception;

/**
 * Tool Registry for storing and discovering MCP Tools.
 */
class ToolRegistry
{
    /**
     * @var array<string, ToolInterface>
     */
    private array $tools = [];

    public function register(ToolInterface $tool): void
    {
        $this->tools[$tool->id()] = $tool;
    }

    public function get(string $id): ToolInterface
    {
        if (!isset($this->tools[$id])) {
            throw new Exception(sprintf('Tool [%s] is not registered.', $id));
        }

        return $this->tools[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->tools[$id]);
    }

    /**
     * Get all registered tools formatted for MCP tools/list response.
     *
     * @return array<array{name: string, description: string, inputSchema: array<string, mixed>}>
     */
    public function toMcpList(): array
    {
        $list = [];
        foreach ($this->tools as $tool) {
            $list[] = [
                'name' => $tool->id(),
                'description' => $tool->description(),
                'inputSchema' => $tool->inputSchema(),
            ];
        }
        return $list;
    }

    /**
     * @return array<string, ToolInterface>
     */
    public function all(): array
    {
        return $this->tools;
    }
}
