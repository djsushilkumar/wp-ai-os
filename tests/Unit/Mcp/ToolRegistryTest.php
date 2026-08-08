<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Mcp;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\Mcp\Tools\AbstractTool;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;

class MockTool extends AbstractTool
{
    public function id(): string
    {
        return 'test_tool';
    }

    public function name(): string
    {
        return 'Test Tool';
    }

    public function description(): string
    {
        return 'Tool for unit testing';
    }

    public function inputSchema(): array
    {
        return ['type' => 'object', 'properties' => ['param' => ['type' => 'string']]];
    }

    public function execute(array $input): mixed
    {
        return ['result' => 'ok'];
    }
}

class ToolRegistryTest extends TestCase
{
    public function testToolRegistryAndMcpFormatting(): void
    {
        $registry = new ToolRegistry();
        $tool = new MockTool();

        $registry->register($tool);

        $this->assertTrue($registry->has('test_tool'));

        $mcpList = $registry->toMcpList();
        $this->assertCount(1, $mcpList);
        $this->assertEquals('test_tool', $mcpList[0]['name']);
        $this->assertEquals('Tool for unit testing', $mcpList[0]['description']);
    }
}
