# WP AI OS (WordPress AI Operating System)

> **Enterprise-Grade AI Operating System for WordPress**  
> Native Model Context Protocol (MCP) Integration Engine & Antigravity Agent IDE Control Center

---

## 🌟 Executive Overview

**WP AI OS** transforms WordPress from a traditional Content Management System into an extensible, machine-discoverable AI compute platform. By embedding a native **Model Context Protocol (MCP)** server directly inside WordPress, WP AI OS enables AI IDEs (such as **Antigravity Agent IDE**) and autonomous AI agents to inspect, manipulate, build, and govern WordPress sites using structured agent abilities.

---

## 🔑 Core Framework Architecture

WP AI OS includes a decoupled, enterprise-grade core framework:

* **Dependency Injection Container (`WPAIOS\Core\Container`):** Full PSR-11 container supporting Singleton, Scoped, and Transient lifetimes with reflection auto-wiring.
* **Event Dispatcher & Hooks Bridge (`WPAIOS\Core\Event`):** Prioritized event listeners, deferred event queueing, and WordPress action/filter hook bridging (`wp_ai_os_*`).
* **Configuration System (`WPAIOS\Services\ConfigLoader`):** Dot-notation array access (`get('app.version')`) and environment auto-detection (`production`, `staging`, `development`, `testing`).
* **Multi-Driver Logger (`WPAIOS\Core\Logger`):** PSR-3 compliant logging pipeline supporting File (`FileLogDriver`) and WordPress error log (`WpLogDriver`) targets.
* **Pluggable Cache Layer (`WPAIOS\Core\Cache`):** Multi-store cache manager with Memory, WordPress Transient, and Object Cache (Redis/Memcached) drivers.
* **Module Manager (`WPAIOS\Core\Module`):** Topological graph dependency resolution, lifecycle control (install, enable, disable, boot), and update orchestration.
* **Lifecycle & Rollback Manager (`WPAIOS\Core\Lifecycle`):** Atomic schema migration runner with full rollback support.
* **Support Utilities (`WPAIOS\Support`):** Strongly-typed helper utilities (`Filesystem`, `Str`, `Arr`, `Json`, `Url`, `Http`, `Validator`, `Sanitizer`, `Nonce`, `Permission`, `Request`, `Response`).

---

## 📂 System Folder Structure

```
wp-ai-os/
├── bootstrap/
│   └── app.php                       # Application instantiation & kernel bootstrap
├── config/
│   ├── app.php                       # Core application settings
│   └── modules.php                   # Enabled platform modules registration
├── docs/                             # Approved Planning & Specs
│   ├── AGENTS.md
│   ├── ARCHITECTURE.md
│   ├── CONTRIBUTING.md
│   ├── PRD.md
│   ├── README.md
│   ├── ROADMAP.md
│   └── TASKS.md
├── src/
│   ├── Contracts/                    # Pure Interfaces & SOLID Contracts
│   │   ├── ActivatorInterface.php
│   │   ├── CacheInterface.php
│   │   ├── ConfigInterface.php
│   │   ├── ContainerInterface.php
│   │   ├── DeactivatorInterface.php
│   │   ├── EventDispatcherInterface.php
│   │   ├── EventListenerInterface.php
│   │   ├── LoggerInterface.php
│   │   ├── MigrationInterface.php
│   │   ├── ModuleInterface.php
│   │   ├── ServiceProviderInterface.php
│   │   └── ValidatorInterface.php
│   ├── Core/                         # Enterprise Core Framework Subsystems
│   │   ├── Cache/                    # Multi-Store Cache Layer (Memory, Transient, ObjectCache)
│   │   ├── Container/                # PSR-11 DI Container (Singleton, Scoped, Transient)
│   │   ├── Event/                    # Prioritized Event Dispatcher & WP Hooks Bridge
│   │   ├── Lifecycle/                # Migration Runner & Rollback Manager
│   │   ├── Logger/                   # PSR-3 Multi-Driver Logger
│   │   └── Module/                   # Module Manager & Topological Graph Resolver
│   ├── Modules/                      # Modular Domain Extensions
│   ├── Providers/                    # Service Provider System
│   │   ├── AbstractServiceProvider.php
│   │   ├── AppServiceProvider.php
│   │   ├── CacheServiceProvider.php
│   │   └── EventServiceProvider.php
│   ├── Services/                     # Core Platform Services (ConfigLoader, UpgradeManager)
│   └── Support/                      # Core Utility Classes
│       ├── Arr.php                   # Dot-notation array utilities
│       ├── Filesystem.php            # Atomic file operations
│       ├── Http.php                  # Remote HTTP client wrapper
│       ├── Json.php                  # Exception-safe JSON encoder/decoder
│       ├── Nonce.php                 # WP Nonce wrapper
│       ├── Permission.php            # Capability gating wrapper
│       ├── Request.php               # HTTP Request wrapper
│       ├── Response.php              # REST Response formatter
│       ├── Sanitizer.php             # Input sanitization
│       ├── Str.php                   # String helpers (slug, camel, snake)
│       ├── Url.php                   # URL builders
│       └── Validator.php             # Rule validator
├── tests/
│   ├── bootstrap.php                 # Test suite bootstrapper
│   └── Unit/                         # Isolated Unit Tests
├── composer.json                     # PSR-4 Autoloading & Dependencies
├── phpcs.xml.dist                    # WPCS ruleset definition
├── phpstan.neon.dist                 # PHPStan Level 8 static analysis rules
├── TODO.md                           # Milestone 2 Next Steps
└── wp-ai-os.php                      # Main Plugin Entrypoint
```

---

## 💻 Example Usage

```php
use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\CacheInterface;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Support\Str;

// 1. Resolve services from DI Container
$container = \WPAIOS\Core\Plugin::instance()->getKernel()->container;

/** @var CacheInterface $cache */
$cache = $container->get(CacheInterface::class);

// 2. Cache with fallback callback
$data = $cache->remember('my_key', 3600, function() {
    return ['status' => 'computed'];
});

// 3. Dispatch prioritized domain event
/** @var EventDispatcherInterface $events */
$events = $container->get(EventDispatcherInterface::class);
$events->dispatch('custom.event_triggered', $data);
```

---

## 🚀 Development & Testing

```bash
# Install PHP dependencies
composer install

# Run static analysis and linting
composer phpstan
composer lint

# Run unit tests
composer test
```

---

## 🔒 Security & Governance

WP AI OS enforces defensive security at every entrypoint. Read-only actions are allowed by default for authenticated agents, while destructive actions (database modifications, post deletions, Elementor layout overwrites) undergo strict capability checks (`manage_options`) and automatic revision snapshotting.

---

## 📄 License
GPLv2 or later.
