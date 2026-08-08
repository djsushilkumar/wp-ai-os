<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Resources;

/**
 * MCP Resource Interface contract.
 */
interface ResourceInterface
{
    public function uri(): string;
    public function name(): string;
    public function description(): string;
    public function mimeType(): string;
    public function read(): mixed;
}
