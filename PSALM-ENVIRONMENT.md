# PSALM-ENVIRONMENT.md — Psalm Analysis Diagnostics & Environment Setup

**Role**: Release Hardening Engineer  
**Date**: August 8, 2026  
**Status**: NOT VERIFIED  

---

## Executive Summary

Psalm static analysis execution was attempted locally; however, the CLI PHP runtime in the current Windows environment lacks native PHP C-extensions required internally by Psalm 6 (`mbstring` extension functions like `mb_strcut`).

---

## Technical Reason for NOT VERIFIED Status

- **Internal Dependency Failure**: Psalm 6's code location analyzer calls `mb_strcut()` during file AST parsing (`Psalm\CodeLocation->calculateRealLocation()`).
- **PHP CLI Environment Constraint**: The local standalone `php.exe` CLI process runs without pre-loaded C-extension DLLs for multibyte string manipulation.
- **Production Code Base Isolation**: Modifying production code to accommodate a tool crash is explicitly forbidden.

---

## Required Environment Setup for Psalm in CI/CD

To make Psalm runnable in automated GitHub Actions / GitLab CI pipelines:

1. **PHP Extension Setup**:
   Ensure `ext-mbstring` and `ext-dom` are explicitly enabled in the runner step:
   ```yaml
   - name: Setup PHP with Extensions
     uses: shivammathur/setup-php@v2
     with:
       php-version: '8.3'
       extensions: mbstring, openssl, curl, zip, dom
   ```

2. **Psalm Command Execution**:
   ```bash
   vendor/bin/psalm --config=psalm.xml --show-info=false
   ```
