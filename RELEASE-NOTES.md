# RELEASE-NOTES.md — WP AI OS v1.0.0

**Release Date**: August 8, 2026  
**Version**: `v1.0.0`  
**License**: GPL-2.0-or-later  

---

## Executive Overview

**WP AI OS v1.0.0** is an enterprise-grade AI Operating System for WordPress that seamlessly extends the Model Context Protocol (MCP) and integrates autonomous multi-agent orchestration, RAG/Knowledge base processing, Elementor AST generation, WooCommerce automation, and provider-independent AI models into a unified WordPress architecture.

---

## Technical Specifications & Requirements

- **Supported PHP Versions**: PHP `8.2.0` or higher (Tested on PHP 8.2 and 8.3).
- **Supported WordPress Versions**: WordPress `6.4` or higher (Tested up to WP 6.5+).
- **Composer Dependencies**:
  - `psr/container`: `^2.0`
  - `psr/log`: `^3.0`
- **MCP Integration Requirement**:
  - Fully supports Model Context Protocol (MCP) JSON-RPC 2.0.
  - Integrates with `WordPress Agent Abilities for MCP` plugin.
  - Includes Standalone Graceful Fallback Mode if MCP plugin is absent.
- **Page Builder Compatibility**:
  - **Elementor**: Version `3.18+` (Flexbox Container AST architecture).
  - **Gutenberg / Block Editor**: WordPress core block engine.
  - **Divi & Bricks**: Supported via `BuilderDocument` AST compiling layer.
- **WooCommerce Compatibility**: WooCommerce `8.0+` (HPOS compatible).

---

## Key Modules Included in v1.0.0

1. **Core Container & Provider Registry**: Lightweight PSR-11 compliant dependency injection container with scoped lifetime support.
2. **Provider-Independent AI Framework**: Unified drivers for OpenAI, Anthropic Gemini/Claude, OpenRouter, Groq, DeepSeek, Ollama, Azure OpenAI, and Vertex AI.
3. **Elementor AST Builder**: Programmatic creation of Flexbox Containers and Widgets with dynamic revision history snapshots.
4. **Autonomous Workflow Engine**: Multi-step pipeline execution with state checkpointing (`wp_ai_os_checkpoints`).
5. **Multi-Agent System**: 13 domain-specific agents, `LoopProtector` depth controls, and `ApprovalManager` for human-in-the-loop gating of `CRITICAL` risk operations.
6. **Enterprise RAG & Knowledge Base**: Native MySQL vector database (`wp_ai_os_vectors`), hybrid search, `PromptInjectionGuard`, and `UrlConnector` SSRF shield.

---

## Verification & Quality Assurance

- **PHPUnit Test Suite**: **PASS** (63 / 63 passed, 169 assertions).
- **PHPStan Static Analysis**: **PASS** (0 errors across 424 files).
- **PHPCS Code Standards**: **PASS** (0 errors across 424 files).
- **Composer Security Audit**: **PASS** (0 security vulnerabilities).

---

## Known Limitations

1. **Psalm Static Analysis Status**: `NOT VERIFIED`. Psalm 6's code parser requires native `mbstring` C-extension functions (`mb_strcut()`) which are unavailable in standard Windows CLI PHP without binary DLL loading. Documented in [PSALM-ENVIRONMENT.md](file:///d:/wp-ai-os/PSALM-ENVIRONMENT.md).
2. **Performance Load Testing**: Benchmarked locally for sub-15ms overhead per request. Synthetic 10,000 concurrent user load testing was not executed in local unit test suite and requires staging load infrastructure.
