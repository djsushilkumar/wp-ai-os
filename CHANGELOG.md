# CHANGELOG.md — WP AI OS Release Notes

All notable changes to **WP AI OS** will be documented in this file.

---

## [v1.0.0] - 2026-08-08 — Enterprise Production Release

### Added
- **Core Platform**: DI Container (`WPAIOS\Core\Container`), PSR-4 autoloading, and singleton registration.
- **MCP Integration Layer**: JSON-RPC 2.0 transport over HTTP, WebSockets, and STDIO with capability checks.
- **Provider-Independent AI Drivers**: Support for Gemini, OpenAI, Claude, OpenRouter, Groq, DeepSeek, Ollama, Azure, and Vertex.
- **Elementor Automation Engine**: Native Flexbox Container AST builder and style token manager.
- **Autonomous Workflow Engine**: Background queueing, step execution, and state checkpointing (`wp_ai_os_checkpoints`).
- **WooCommerce Enterprise Module**: Product, inventory, order, and coupon management abilities.
- **Enterprise SEO Engine**: Schema.org JSON-LD generator and meta tag inspector.
- **Enterprise Media Platform**: MIME type validation and metadata sync.
- **Enterprise Forms Platform**: Provider-independent abstraction layer supporting Fluent Forms, Gravity Forms, WPForms, Contact Form 7, Ninja Forms, Formidable Forms, and Native Fallback.
- **Multi-Builder & Theme Abstraction**: Common `BuilderDocument` AST compiling to Elementor, Gutenberg, Bricks, and Divi, with Block and Classic theme adapters.
- **Multi-Agent Orchestration System**: 13 built-in specialized agents, `LoopProtector`, `ApprovalManager` for `CRITICAL` risk tasks, and immutable audit logging.
- **Enterprise Knowledge Base & RAG Platform**: Hybrid keyword + vector search, native MySQL vector store (`wp_ai_os_vectors`), `PromptInjectionGuard`, `UrlConnector` SSRF protection, and `PermissionFilter` for post visibility and multisite isolation.
