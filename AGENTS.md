# AGENTS.md - Agent Interoperability & MCP Standard

## WP AI OS Agent Interoperability Guide

This document defines the protocols, guidelines, and safety boundaries for AI Agents (including **Antigravity Agent IDE**, autonomous background agents, and CLI agents) interacting with **WP AI OS**.

---

## 1. Connection Protocols & Transports

WP AI OS implements the **Model Context Protocol (MCP)** specification over three transports:

### 1.1 Server-Sent Events (SSE) Transport (Recommended for IDEs)
- **URL:** `https://your-domain.com/wp-json/wp-ai-os/v1/mcp/sse`
- **Authentication:** HTTP Header `Authorization: Bearer <WP_AI_OS_JWT_TOKEN>` or Application Passwords.
- **Protocol:** Real-time stream for receiving tool responses and execution progress.

### 1.2 HTTP POST Transport
- **URL:** `https://your-domain.com/wp-json/wp-ai-os/v1/mcp`
- **Method:** `POST`
- **Headers:** 
  - `Content-Type: application/json`
  - `Authorization: Bearer <WP_AI_OS_JWT_TOKEN>`
- **Body:** Standard JSON-RPC 2.0 requests.

### 1.3 WP-CLI Stdin/Stdout Transport (Local Agent Execution)
- **Command:** `wp ai-os mcp-server --user=admin`
- **Transport:** Raw Stdin/Stdout JSON-RPC 2.0 pipe. Recommended for local Antigravity Agent IDE operations running on the same host machine.

---

## 2. Standard MCP Protocol Lifecycle

```
[Agent Client]                                       [WP AI OS Server]
     │                                                       │
     ├────────── 1. initialize ─────────────────────────────>│
     │<───────── Capabilities & Server Meta ─────────────────┤
     │                                                       │
     ├────────── 2. tools/list ─────────────────────────────>│
     │<───────── Dynamic Abilities & JSON Schemas ───────────┤
     │                                                       │
     ├────────── 3. tools/call (wp_ai_os_...) ──────────────>│
     │<───────── Execution Result / Status Payload ──────────┤
```

---

## 3. Discovered Abilities (MCP Tools) Reference

Agents query the `tools/list` endpoint to discover active WordPress abilities. All tools follow the prefix `wp_ai_os_*`.

### 3.1 Content Abilities
* **`wp_ai_os_get_posts`**
  - **Description:** Retrieve posts or custom post types with filtering parameters.
  - **Params Schema:** `{ "post_type": string, "post_status": string, "numberposts": integer, "s": string }`
* **`wp_ai_os_create_post`**
  - **Description:** Create a new post, page, or custom post type.
  - **Params Schema:** `{ "post_type": string, "post_title": string, "post_content": string, "post_status": string, "meta_input": object }`
* **`wp_ai_os_update_post`**
  - **Description:** Modify existing post attributes or meta fields.
  - **Params Schema:** `{ "ID": integer, "post_title": string, "post_content": string, "post_status": string }`

### 3.2 Elementor Engine Abilities
* **`wp_ai_os_inspect_elementor_layout`**
  - **Description:** Return parsed Flexbox Container tree structure and widget parameters for a given post ID.
  - **Params Schema:** `{ "post_id": integer }`
* **`wp_ai_os_create_elementor_page`**
  - **Description:** Assemble a dynamic Elementor page using structured Flexbox Containers and Widgets. Automates revision snapshot creation before saving.
  - **Params Schema:** `{ "title": string, "slug": string, "containers": array, "kit_theme": string }`
* **`wp_ai_os_update_elementor_widget`**
  - **Description:** Safely modify specific widget settings or design tokens inside an Elementor page.
  - **Params Schema:** `{ "post_id": integer, "widget_id": string, "settings": object }`

### 3.3 System & Security Abilities
* **`wp_ai_os_get_system_info`**
  - **Description:** Returns PHP, WordPress core, active theme, installed plugins, and database health metrics.
  - **Params Schema:** `{}`
* **`wp_ai_os_safe_db_query`**
  - **Description:** Executes read-only SQL queries via `wpdb->prepare()`. Destructive queries are rejected unless run under administrative sandbox authorization.
  - **Params Schema:** `{ "query": string, "params": array }`

---

## 4. Agent Safety & Execution Constraints

To maintain enterprise integrity, AI agents **MUST** follow these runtime rules when operating WP AI OS:

1. **Schema Compliance:** Input arguments must match the JSON Schema provided during `tools/list`. Extra un-validated keys will be stripped by the `Sanitizer` module.
2. **Revision Awareness:** When updating Elementor templates or core posts, agents should verify revision snapshots were successfully created in the tool output before executing dependent tasks.
3. **Token & Rate Quotas:** If an agent receives error code `-32001 (Quota Exceeded)`, it must pause execution and wait for the quota window reset or notify the human operator.
4. **Idempotency:** Re-running a tool with identical parameters should not degrade site layout. Design widget updates using explicit widget IDs when possible.
5. **No Direct HTML Mutation for Elementor:** Agents must use `wp_ai_os_create_elementor_page` or `wp_ai_os_update_elementor_widget` rather than writing raw HTML into `post_content` for Elementor pages.

---

## 5. JSON-RPC Error Codes

| Code | Message | Description |
| :--- | :--- | :--- |
| `-32700` | Parse error | Invalid JSON received by the server. |
| `-32600` | Invalid Request | Payload does not conform to JSON-RPC 2.0. |
| `-32601` | Method not found | Requested MCP method or tool does not exist. |
| `-32602` | Invalid params | Tool arguments failed JSON Schema validation. |
| `-32000` | Permission Denied | Authenticated user lacks required WP capability. |
| `-32001` | Quota Exceeded | User/Key has exceeded token budget or rate limit. |
| `-32002` | Elementor Error | AST validation or layout compilation failed. |
