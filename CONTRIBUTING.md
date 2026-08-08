# Contributing to WP AI OS

Thank you for contributing to WP AI OS! To maintain enterprise quality standards, all contributions must pass our strict Quality Gate pipeline.

---

## 🔒 Mandatory Pull Request Criteria

All PRs must meet the following automated criteria before merging into `main` or `develop`:

1. **Security**: `composer audit` returns 0 vulnerabilities.
2. **PHPStan**: Zero errors at **Level Max**.
3. **Psalm**: Zero errors at **Level 1 (Level Max)**.
4. **WPCS**: Zero PHPCS code standard violations.
5. **Code Coverage**: Minimum **90% Unit Test Coverage** via PHPUnit.
6. **Mutation Testing**: Minimum **80% MSI (Mutation Score Indicator)** via Infection PHP.

---

## 🛠️ Local Verification

Before creating a Pull Request, run:

```bash
composer quality-gate
```
