<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Resources;

/**
 * Abstract Resource base class for MCP Resources (Files, Posts, Pages, Media, Templates, Settings, Logs, Configuration).
 */
abstract class AbstractResource implements ResourceInterface
{
    protected string $mimeType = 'application/json';

    public function mimeType(): string
    {
        return $this->mimeType;
    }
}
