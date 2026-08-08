# MCP Tools API Specification - WP AI OS

## Overview

Tools represent functional utilities exposed directly to Model Context Protocol (MCP) clients.

---

## ToolInterface Contract

Every Tool class MUST implement `WPAIOS\Modules\Mcp\Tools\ToolInterface`:

```php
namespace WPAIOS\Modules\Mcp\Tools;

interface ToolInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;
    public function inputSchema(): array;
    public function outputSchema(): array;
    public function validate(array $input): bool;
    public function authorize(): bool;
    public function execute(array $input): mixed;
}
```

---

## Tool Discovery Format (`tools/list`)

Tools registered in `ToolRegistry` are automatically exposed in standard MCP JSON-RPC format:

```json
{
  "tools": [
    {
      "name": "wp_ai_os_inspect_site",
      "description": "Inspect WordPress site system health metrics",
      "inputSchema": {
        "type": "object",
        "properties": {}
      }
    }
  ]
}
```
