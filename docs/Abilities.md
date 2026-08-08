# Agent Abilities API Specification - WP AI OS

## Overview

Abilities in **WP AI OS** represent machine-discoverable actions that AI Agents (such as **Antigravity Agent IDE**) can query and execute.

---

## AbilityInterface Contract

Every Ability class MUST implement `WPAIOS\Modules\Mcp\Abilities\AbilityInterface`:

```php
namespace WPAIOS\Modules\Mcp\Abilities;

interface AbilityInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;
    public function version(): string;
    public function permissions(): array;
    public function schema(): array;
    public function execute(array $params): mixed;
    public function validate(array $params): bool;
    public function authorize(): bool;
    public function metadata(): array;
    public function health(): array;
}
```

---

## Creating a Custom Ability

```php
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

class GetSiteStatsAbility extends AbstractAbility
{
    public function id(): string
    {
        return 'wp_ai_os_get_site_stats';
    }

    public function name(): string
    {
        return 'Get Site Statistics';
    }

    public function description(): string
    {
        return 'Returns post count, user count, and active plugin metrics.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'include_plugins' => ['type' => 'boolean', 'default' => true],
            ],
        ];
    }

    public function execute(array $params): mixed
    {
        return [
            'posts' => wp_count_posts()->publish,
            'users' => count_users()['total_users'],
        ];
    }
}
```

---

## Registering Custom Abilities

```php
use WPAIOS\Modules\Mcp\Abilities\AbilityRegistry;

$abilityRegistry = $container->get(AbilityRegistry::class);
$abilityRegistry->register(new GetSiteStatsAbility());
```
