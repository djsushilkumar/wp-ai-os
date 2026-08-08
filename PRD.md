# Product Requirements Document (PRD)

## Project: WP AI OS (WordPress AI Operating System)
**Version:** 1.0.0-DRAFT  
**Status:** Architecture & Planning Phase  
**Author:** Lead Software Architect & WordPress Engineer  
**Target Environment:** PHP 8.2+, WordPress 6.4+, Composer 2.x  

---

## 1. Executive Summary & Vision

**WP AI OS** is an enterprise-grade AI Operating System designed natively for WordPress. It acts as an intelligent orchestration layer embedded inside WordPress, exposing system capabilities via the **Model Context Protocol (MCP)** to external AI IDEs (such as Antigravity Agent IDE) and local/cloud autonomous agents.

Rather than being a simple chatbot or single-purpose prompt wrapper, WP AI OS treats WordPress as an extensible compute platform. It converts core WordPress subsystems—Post Type registries, Taxonomies, Users, Media Library, Database queries, Dynamic Hooks, and Page Builders (specifically Elementor)—into machine-discoverable, policy-governed **Agent Abilities**.

---

## 2. Core Goals & Objectives

1. **Native MCP Server Capabilities:** Implement a full Model Context Protocol (MCP) server supporting JSON-RPC 2.0 over Server-Sent Events (SSE), HTTP POST, and CLI transports to connect securely with Antigravity IDE and external agent clients.
2. **Modular & Extensible Architecture:** Fully compliant with PHP 8.2+, PSR-4 (`WPAIOS\`), PSR-11 (Container), PSR-3 (Logging), and WordPress Coding Standards (WPCS).
3. **Multi-Provider AI Abstraction:** Unified LLM provider registry supporting OpenAI, Anthropic, Google Gemini, Local Ollama, and custom REST API endpoints with streaming, function calling, rate limiting, and automated fallback.
4. **Deep Elementor Integration Engine:** Structured AST-level manipulation of Elementor section/container layouts, widget generation, design kit tokens, dynamic content bindings, and safe visual snapshot diffing.
5. **Zero-Trust Security & Governance:** Role-Based Access Control (RBAC), capability gating (`manage_options`, custom caps), execution sandboxing for destructive actions, immutable audit logs, prompt injection sanitization, and token usage quota management.
6. **Production & Developer Readiness:** Built with unit testing (PHPUnit, Brain Monkey), static analysis (PHPStan Level 8), CI/CD pipelines, CLI tooling (`wp ai-os ...`), and comprehensive documentation.

---

## 3. User Personas & Use Cases

### 3.1 Personas
* **Enterprise Site Administrators:** Require fine-grained access control, audit trails, and strict cost/token management.
* **AI Developers & Engineers:** Building agent workflows in Antigravity IDE or autonomous agents needing programmatic, context-aware access to WordPress resources.
* **WordPress Agencies & Designers:** Leveraging automated Elementor layout generation, content migration, and site orchestration using natural language agent commands.

### 3.2 Primary Use Cases
* **Remote Agent IDE Control:** Antigravity IDE connects to WP AI OS via MCP, discovers available WordPress tools/abilities, and executes complex multi-step workflows (e.g., "Build a landing page using Elementor containers, configure SEO metadata, and draft 3 related blog posts").
* **Autonomous Maintenance Agent:** Background scheduled agent that audits site performance, detects database clutter, sanitizes user content, and updates Elementor templates according to design guidelines.
* **Intelligent Elementor Composition:** Automated generation of complex nested Elementor pages using structured JSON specs instead of fragile raw HTML generation.

---

## 4. Key Functional Requirements (FR)

### FR-1: Core Kernel & Module Lifecycle
- **FR-1.1:** PSR-4 dependency injection container managing module registration and lifecycle (`boot`, `register`, `shutdown`).
- **FR-1.2:** Configurable feature toggles enabling or disabling individual modules dynamically.

### FR-2: AI Provider Abstraction Layer
- **FR-2.1:** Standardized `ProviderInterface` supporting text completion, chat completion, JSON schema output, and embeddings.
- **FR-2.2:** Multi-provider support: OpenAI (GPT-4o), Anthropic (Claude 3.5 Sonnet), Gemini (Gemini 1.5 Pro/Flash), Ollama (Llama 3/Mistral), and Custom OpenAI-compatible endpoints.
- **FR-2.3:** Robust failover orchestration (e.g., automatically failover from primary provider to secondary on API error or rate limit).
- **FR-2.4:** Streaming support (SSE & Chunked responses) for real-time output rendering.

### FR-3: Model Context Protocol (MCP) Integration Engine
- **FR-3.1:** Full MCP Specification compliance (Tool discovery, Resources, Prompts, SSE transport, JSON-RPC 2.0).
- **FR-3.2:** Dynamic capability discovery mapping WordPress actions to MCP Tools (`wp_ai_os_list_posts`, `wp_ai_os_create_elementor_page`, `wp_ai_os_run_db_query`, etc.).
- **FR-3.3:** Bidirectional communication: Agents can query site state, request context, or trigger abilities.

### FR-4: Agent Abilities Registry
- **FR-4.1:** Declarative register for registering new tools with explicit input schemas (JSON Schema Draft 7), capability requirements, and execution callbacks.
- **FR-4.2:** Core Built-in Abilities:
  - **Content Ability:** CRUD for Posts, Pages, Custom Post Types, Meta fields, Taxonomies.
  - **User & Security Ability:** Role check, User management, Nonce/Token generation.
  - **Media Ability:** Media library upload, metadata inspection, image optimization trigger.
  - **System Ability:** Database health inspection, WP-Cron status, Plugin/Theme inventory.
  - **Elementor Ability:** Inspect templates, inject elements, update page kit settings.

### FR-5: Elementor Integration Engine
- **FR-5.1:** Parse, inspect, validate, and manipulate Elementor JSON page structure (Containers, Sections, Columns, Widgets).
- **FR-5.2:** Elementor Design Kit Token Binding (Colors, Typography, Spacing presets).
- **FR-5.3:** Safe transaction mode: Automatic revision backup before applying AI-generated layout mutations, allowing 1-click restore.

### FR-6: Security, Governance & Audit Logging
- **FR-6.1:** Application-level API Key and JWT authentication for agent endpoints.
- **FR-6.2:** Policy Gating: Granular permission checks prior to tool execution (e.g., `can_delete_post`, `can_modify_elementor_template`).
- **FR-6.3:** Sandbox Mode: High-risk tools (DB update, option deletion, code execution) require explicit admin confirmation or dry-run evaluation.
- **FR-6.4:** Immutable Audit Logger recording tool invocations, requesting agent metadata, execution latency, and success/failure status.

---

## 5. Non-Functional Requirements (NFR)

* **NFR-1 Performance:** API response latency overhead under 15ms for core routing and ability dispatching (excluding LLM execution latency).
* **NFR-2 Security:** Zero OWASP Top 10 vulnerabilities, strict sanitization/escaping via WordPress core functions (`wp_kses`, `sanitize_text_field`, `prepare`), protection against prompt injection vector leakage.
* **NFR-3 Compatibility:** PHP 8.2 through 8.4 support; WordPress 6.4 through 6.7+ support; Elementor 3.20+ support.
* **NFR-4 Maintainability:** Strict adherence to WordPress Coding Standards (WPCS) enforced via PHP_CodeSniffer; PHPStan Level 8 static analysis compliance.
* **NFR-5 Extensibility:** Action hooks and filters exposed for 3rd-party developers (`wp_ai_os_register_ability`, `wp_ai_os_pre_execute_tool`, `wp_ai_os_provider_request`).

---

## 6. Enterprise Risk & Mitigation Strategy

| Identified Risk | Risk Severity | Proposed Mitigation Strategy |
| :--- | :--- | :--- |
| **Prompt Injection Attacks** | High | Input validation using JSON Schema, strict output sanitization, policy-based parameter whitelisting. |
| **Accidental Site Breakage via Elementor** | High | Automatic WP Revision snapshotting prior to write operations; schema validation of Elementor data arrays before saving. |
| **LLM Provider Downtime / Rate Limits** | Medium | Multi-provider abstraction with configurable automatic fallback routes and exponential backoff retries. |
| **Unbounded Token Usage & API Costs** | Medium | Daily/Monthly token quota limits configured per user role or API key; real-time token tracking. |
| **WP Core Version Breaking Changes** | Low | Isolation of WP Core calls inside Adapter interfaces; comprehensive automated test suite (PHPUnit). |

---

## 7. Out of Scope for Release 1.0

* Client-side UI visual drag-and-drop workflow canvas (Focus is backend MCP engine + Antigravity IDE connection).
* Fine-tuning local LLMs directly inside WordPress server processes.
* Direct PHP code execution engine on production environments without sandbox validation.
