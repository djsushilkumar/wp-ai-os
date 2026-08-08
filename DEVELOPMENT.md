# WP AI OS — Developer Guide & Quality Gate Pipeline

## 🎯 Quality Gate Mandatory Thresholds

Every Pull Request must pass **all** 7 automated Quality Gate checks in CI:

| Tool | Mandated Level / Threshold | Command |
| :--- | :--- | :--- |
| **Composer Audit** | Zero known security vulnerabilities | `composer audit` |
| **PHPCS (WPCS)** | Zero violations (`WordPress-Core`, `VIP-Go`) | `composer lint` |
| **PHP-CS-Fixer** | Strict PSR-12 formatting check | `composer format:check` |
| **PHPStan** | **Level Max (Level 9)** | `composer phpstan` |
| **Psalm** | **Level 1 (Level Max)** | `composer psalm` |
| **PHPUnit** | **Minimum 90% Code Coverage** | `composer test:coverage` |
| **Infection PHP** | **Minimum 80% Mutation Score (MSI)** | `composer test:mutation` |

---

## 🚀 Local Execution

Run the complete Quality Gate locally before submitting a PR:

```bash
composer quality-gate
```

---

## 🛠️ Individual Commands

- **Run Tests**: `composer test`
- **Check Test Coverage**: `composer test:coverage`
- **Run Mutation Testing**: `composer test:mutation`
- **Run PHPStan**: `composer phpstan`
- **Run Psalm**: `composer psalm`
- **Check WPCS Formatting**: `composer lint`
- **Auto-fix Formatting**: `composer lint:fix` & `composer format`
