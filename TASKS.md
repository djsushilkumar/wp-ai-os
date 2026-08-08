# TASKS.md - WP AI OS Actionable Task Inventory

## Immediate Task Breakdown (Phase 1 & Phase 2 Foundation)

**Status Legend:**
- `[ ]` Not Started
- `[IP]` In Progress
- `[X]` Completed

---

## Group 1: Repository Foundation & Standards Setup
- [ ] **TASK-101: Composer Infrastructure & Autoloading**
  - **Path:** `composer.json`
  - **Priority:** High
  - **Acceptance Criteria:** PSR-4 mapping `WPAIOS\` -> `src/`, PHP requirement `^8.2`, autoloading phpunit, brain-monkey, phpstan, phpcs.
- [ ] **TASK-102: WordPress Coding Standards & Static Analysis Config**
  - **Path:** `phpcs.xml.dist`, `phpstan.neon.dist`
  - **Priority:** High
  - **Acceptance Criteria:** PHP_CodeSniffer configured for WPCS (WordPress-Extra), PHPStan set to Level 8.
- [ ] **TASK-103: Core Bootstrapper & Main Plugin File**
  - **Path:** `wp-ai-os.php`
  - **Priority:** High
  - **Acceptance Criteria:** Plugin header metadata, PHP version check (`VERSION_COMPATIBLE`), constant definitions, lifecycle activation/deactivation hooks.

---

## Group 2: Core Framework Engine
- [ ] **TASK-201: PSR-11 Dependency Injection Container**
  - **Path:** `src/Core/Container.php`, `tests/Unit/ContainerTest.php`
  - **Priority:** High
  - **Acceptance Criteria:** Implement `has()`, `get()`, `bind()`, `singleton()`. 100% unit test coverage.
- [ ] **TASK-202: Event Dispatcher & Bus**
  - **Path:** `src/Core/EventDispatcher.php`, `tests/Unit/EventDispatcherTest.php`
  - **Priority:** Medium
  - **Acceptance Criteria:** `dispatch()`, `listen()`, integration with WordPress action hooks.
- [ ] **TASK-203: Plugin Kernel Lifecycle Controller**
  - **Path:** `src/Core/Kernel.php`, `src/Core/Plugin.php`
  - **Priority:** High
  - **Acceptance Criteria:** Orderly boot sequence: Load config -> Init container -> Register providers -> Boot MCP Server -> Hook WP admin/REST endpoints.

---

## Group 3: AI Provider Layer Implementation
- [ ] **TASK-301: Provider Interfaces & Data Models**
  - **Path:** `src/Providers/ProviderInterface.php`, `src/Providers/Models/*`
  - **Priority:** High
  - **Acceptance Criteria:** `Request`, `Response`, `ToolCall` value objects created with strict parameter typing.
- [ ] **TASK-302: OpenAI Provider Driver**
  - **Path:** `src/Providers/OpenAI/OpenAIProvider.php`, `tests/Unit/OpenAIProviderTest.php`
  - **Priority:** High
  - **Acceptance Criteria:** Support chat completions, SSE streaming, function calling. Unit tests with mocked HTTP responses.
- [ ] **TASK-303: Anthropic Provider Driver**
  - **Path:** `src/Providers/Anthropic/AnthropicProvider.php`
  - **Priority:** High
  - **Acceptance Criteria:** Claude 3.5 Sonnet support, Tool formatting mapping.
- [ ] **TASK-304: Gemini & Ollama Provider Drivers**
  - **Path:** `src/Providers/Gemini/GeminiProvider.php`, `src/Providers/Ollama/OllamaProvider.php`
  - **Priority:** Medium
  - **Acceptance Criteria:** Google Gemini API & Local Ollama REST endpoint support.
- [ ] **TASK-305: Provider Registry & Circuit Breaker**
  - **Path:** `src/Providers/ProviderRegistry.php`
  - **Priority:** High
  - **Acceptance Criteria:** Execute primary provider with automatic failover fallback list on failure.

---

## Group 4: Model Context Protocol (MCP) Server
- [ ] **TASK-401: MCP JSON-RPC Server Core**
  - **Path:** `src/Mcp/Server.php`, `src/Mcp/Protocol/*`
  - **Priority:** High
  - **Acceptance Criteria:** Parse JSON-RPC 2.0 requests, handle error codes, route methods to registered handlers.
- [ ] **TASK-402: MCP Methods Handlers**
  - **Path:** `src/Mcp/Handlers/InitializeHandler.php`, `ToolsListHandler.php`, `ToolsCallHandler.php`
  - **Priority:** High
  - **Acceptance Criteria:** Dynamic JSON Schema output for active tools.
- [ ] **TASK-403: MCP HTTP, SSE & CLI Transports**
  - **Path:** `src/Mcp/Transports/HttpTransport.php`, `SseTransport.php`, `CliTransport.php`
  - **Priority:** High
  - **Acceptance Criteria:** REST API endpoint `/wp-json/wp-ai-os/v1/mcp` and WP-CLI command `wp ai-os mcp-server`.

---

## Group 5: Base WordPress Agent Abilities
- [ ] **TASK-501: Abilities Registry Architecture**
  - **Path:** `src/Abilities/AbilityRegistry.php`, `AbstractAbility.php`
  - **Priority:** High
  - **Acceptance Criteria:** Declarative tool registration, capability verification, JSON Schema validation.
- [ ] **TASK-502: Content Abilities (CRUD)**
  - **Path:** `src/Abilities/Content/GetPostsAbility.php`, `CreatePostAbility.php`, `UpdatePostAbility.php`
  - **Priority:** High
  - **Acceptance Criteria:** WP Core post creation/retrieval with error handling and meta support.
- [ ] **TASK-503: System Info & Database Abilities**
  - **Path:** `src/Abilities/System/GetSystemInfoAbility.php`, `src/Abilities/Database/SafeQueryAbility.php`
  - **Priority:** Medium
  - **Acceptance Criteria:** System diagnostics and safe parameterized SQL execution.

---

## Group 6: Elementor Deep Integration Engine
- [ ] **TASK-601: Elementor AST Parser & Node Models**
  - **Path:** `src/Elementor/Ast/Node.php`, `ContainerNode.php`, `WidgetNode.php`
  - **Priority:** High
  - **Acceptance Criteria:** Clean object-oriented representation of `_elementor_data` JSON.
- [ ] **TASK-602: Flexbox Container Builder**
  - **Path:** `src/Elementor/Builders/PageBuilder.php`, `WidgetFactory.php`
  - **Priority:** High
  - **Acceptance Criteria:** Generate container layouts with dynamic nested child elements.
- [ ] **TASK-603: Design Kit Token Resolver**
  - **Path:** `src/Elementor/Kit/KitTokenResolver.php`
  - **Priority:** Medium
  - **Acceptance Criteria:** Resolve site active color and font variables into Elementor widget settings.
- [ ] **TASK-604: Safety Snapshot Engine & Elementor Abilities**
  - **Path:** `src/Elementor/ElementorBridge.php`, `src/Abilities/Elementor/*`
  - **Priority:** High
  - **Acceptance Criteria:** Automatic post revision creation prior to meta save. Expose layout creation tools to MCP.

---

## Group 7: Governance & Security Sandbox
- [ ] **TASK-701: Capability Guard & Sanitizer**
  - **Path:** `src/Security/CapabilityGuard.php`, `Sanitizer.php`
  - **Priority:** High
  - **Acceptance Criteria:** Verify capabilities (`manage_options`) and sanitize tool parameters.
- [ ] **TASK-702: Immutable Audit Logger**
  - **Path:** `src/Security/AuditLogger.php`
  - **Priority:** High
  - **Acceptance Criteria:** Create custom database table `wp_ai_os_audit_log` and log tool executions.
