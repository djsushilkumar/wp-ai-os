<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Tools;

/**
 * Generic Tool Interface contract for MCP tools.
 */
interface ToolInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;

    /**
     * JSON Schema for tool input parameters.
     *
     * @return array<string, mixed>
     */
    public function inputSchema(): array;

    /**
     * JSON Schema for tool output payload.
     *
     * @return array<string, mixed>
     */
    public function outputSchema(): array;

    public function validate(array $input): bool;
    public function authorize(): bool;
    public function execute(array $input): mixed;
}
