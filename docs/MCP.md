# MCP Integration Architecture - WP AI OS

## Overview

**WP AI OS** acts as an enterprise-grade extension layer for **WordPress Agent Abilities for MCP**. It automatically detects the existence of the WordPress Agent Abilities MCP plugin and connects native abilities, tools, resources, prompts, and workflows to the MCP server.

---

## Graceful Fallback Mode

If the WordPress Agent Abilities MCP plugin is not active on the WordPress site:
1. **WP-Admin Notice:** Displays a dismissible warning notice to site administrators.
2. **Safe Feature Gating:** MCP-dependent endpoints and bridge hooks are safely paused.
3. **Standalone Operational Mode:** Core framework, logger, event bus, and standalone plugins continue executing safely without crashes or fatal errors.

---

## Subsystem Architecture

```
                               ┌────────────────────────────────┐
                               │  WP AI OS McpManager           │
                               └───────────────┬────────────────┘
                                               │
                      ┌────────────────────────┴────────────────────────┐
                      ▼                                                 ▼
          [MCP Plugin Detected]                              [MCP Plugin Missing]
                      │                                                 │
          ┌───────────┴───────────┐                         ┌───────────┴───────────┐
          │ Boot McpBridge        │                         │ Safe Standalone Mode  │
          │ Register Extension    │                         │ Display Admin Notice  │
          │ Hooks                 │                         └───────────────────────┘
          └───────────┬───────────┘
                      │
   ┌──────────────────┼──────────────────┬──────────────────┐
   ▼                  ▼                  ▼                  ▼
AbilityRegistry    ToolRegistry    ResourceRegistry   PromptRegistry
```

---

## Protocol Endpoints
- **HTTP JSON-RPC 2.0 Endpoint:** `/wp-json/wp-ai-os/v1/mcp`
- **Server-Sent Events (SSE) Endpoint:** `/wp-json/wp-ai-os/v1/mcp/sse`
- **WP-CLI Pipe Command:** `wp ai-os mcp-server`
