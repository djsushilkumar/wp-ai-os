<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Resources;

use Exception;

/**
 * Resource Registry managing MCP resources (Posts, Media, Templates, Settings, Logs).
 */
class ResourceRegistry
{
    /**
     * @var array<string, ResourceInterface>
     */
    private array $resources = [];

    public function register(ResourceInterface $resource): void
    {
        $this->resources[$resource->uri()] = $resource;
    }

    public function get(string $uri): ResourceInterface
    {
        if (!isset($this->resources[$uri])) {
            throw new Exception(sprintf('Resource [%s] is not registered.', $uri));
        }

        return $this->resources[$uri];
    }

    public function has(string $uri): bool
    {
        return isset($this->resources[$uri]);
    }

    /**
     * Format resources list for MCP resources/list JSON-RPC response.
     *
     * @return array<array{uri: string, name: string, description: string, mimeType: string}>
     */
    public function toMcpList(): array
    {
        $list = [];
        foreach ($this->resources as $res) {
            $list[] = [
                'uri' => $res->uri(),
                'name' => $res->name(),
                'description' => $res->description(),
                'mimeType' => $res->mimeType(),
            ];
        }
        return $list;
    }

    /**
     * @return array<string, ResourceInterface>
     */
    public function all(): array
    {
        return $this->resources;
    }
}
