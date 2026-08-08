# Integration Framework Code Examples — WP AI OS

## Executing Discovery Scan via DI Container

```php
use WPAIOS\Modules\Integration\Discovery\PluginDiscoveryManager;

// 1. Resolve PluginDiscoveryManager from Container
$discovery = $container->get(PluginDiscoveryManager::class);

// 2. Discover active plugin adapters
$report = $discovery->discover();

print_r($report);
```

---

## Discovering Integrations via MCP Ability

```json
{
    "tool": "wp_ai_os_discover_integrations",
    "arguments": {}
}
```
