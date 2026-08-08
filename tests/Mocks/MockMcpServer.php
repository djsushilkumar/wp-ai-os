<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Mocks;

/**
 * Mock MCP Server for testing tool/resource/prompt registration hooks.
 */
class MockMcpServer
{
    /** @var array<string, mixed> */
    public array $registeredTools = [];
    /** @var array<string, mixed> */
    public array $registeredResources = [];
    /** @var array<string, mixed> */
    public array $registeredPrompts = [];

    public function registerTool(string $name, array $schema): void
    {
        $this->registeredTools[$name] = $schema;
    }

    public function registerResource(string $uri, array $meta): void
    {
        $this->registeredResources[$uri] = $meta;
    }

    public function registerPrompt(string $name, array $arguments): void
    {
        $this->registeredPrompts[$name] = $arguments;
    }
}
