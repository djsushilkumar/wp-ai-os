# WP AI OS — Roadmap V2 & Architectural Hardening Plan

## Release Milestones & Phase Overview

```
Phase 1: Architectural Hardening & Tech Debt Remediation (v1.1.0)
   ├── Security Hardening (AUTH_KEY enforcement)
   ├── Options DB Bloat Fix (Custom DB Tables for Queue & Checkpoints)
   └── WPCS & CSRF Nonce Verification

Phase 2: E-Commerce & Business Intelligence (v1.2.0)
   ├── WooCommerce Automation Module (Product, Order, Inventory Managers)
   └── E-Commerce Autonomous Workflows

Phase 3: Autonomous SEO Engine (v1.3.0)
   ├── Schema.org JSON-LD Generator
   ├── Meta Description & Heading Optimizer
   └── Automated Content Inspector

Phase 4: Full AI Site Builder Engine (v2.0.0)
   ├── Complete Site Generator (Multi-page Elementor AST synthesis)
   ├── Vector Database Integration (Pinecone / Qdrant / Local Vector Search)
   └── Multi-Agent Collaboration Engine
```

---

## Technical Hardening Deliverables for v1.1.0

1. **Database Schema Creation (`wp_ai_os_queue` & `wp_ai_os_checkpoints`)**:
   - Create custom MySQL tables via `dbDelta()` on plugin activation to replace `wp_options` storage for queue items and checkpoints.

2. **Strict AUTH_KEY Assertion**:
   - Rejection of fallback static salts in `KeyEncryptor`.

3. **REST API Endpoint Registration**:
   - Register `/wp-json/wp-ai-os/v1/mcp` endpoints with `permission_callback` checking `current_user_can('manage_options')` and application password / bearer token auth.
