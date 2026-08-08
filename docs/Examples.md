# Code Examples - WP AI OS AI Provider Framework

## Basic Chat Completion with Automatic Fallback

```php
use WPAIOS\Modules\AI\Models\Message;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Providers\ProviderRegistry;

// 1. Resolve ProviderRegistry from DI Container
$registry = $container->get(ProviderRegistry::class);

// 2. Prepare standardized Request object
$request = new Request(
    messages: [
        new Message('system', 'You are the WP AI OS Autonomous Agent.'),
        new Message('user', 'Inspect WordPress site health and list active plugins.')
    ],
    temperature: 0.2
);

// 3. Execute with circuit-breaker fallback chain
$response = $registry->executeWithFallback($request, 'openai');

echo "Model Used: " . $response->model . "\n";
echo "Response Content: " . $response->content . "\n";
```
