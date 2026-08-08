# Adding Custom LLM Providers - Developer Guide

## Overview

Adding a new AI provider to **WP AI OS** requires implementing a single class extending `WPAIOS\Modules\AI\Providers\AbstractAIProvider`.

---

## Step 1: Create the Provider Class

```php
namespace WPAIOS\Modules\AI\Providers\Drivers;

use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Models\Usage;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;

class MyCustomProvider extends AbstractAIProvider
{
    public function getName(): string
    {
        return 'my_custom_provider';
    }

    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, ['chat', 'streaming'], true);
    }

    public function chat(Request $request): Response
    {
        $url = 'https://api.myprovider.com/v1/chat';
        $headers = ['Authorization' => 'Bearer ' . ($this->config['api_key'] ?? '')];

        $raw = $this->postJson($url, $headers, [
            'model' => $request->model ?? 'default-model',
            'messages' => array_map(fn($m) => $m->toArray(), $request->messages),
        ]);

        return new Response(
            content: $raw['output'] ?? '',
            model: $request->model ?? 'default-model',
            usage: new Usage()
        );
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}
```

---

## Step 2: Register in Provider Registry

```php
$registry = $container->get(\WPAIOS\Modules\AI\Providers\ProviderRegistry::class);
$registry->register(new MyCustomProvider(['api_key' => 'secret_key']));
```
