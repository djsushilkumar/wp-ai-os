# Creating Custom Plugin Adapters — WP AI OS

## PluginAdapterInterface Contract

```php
namespace WPAIOS\Modules\Integration\Contracts;

interface PluginAdapterInterface
{
    public function id(): string;
    public function name(): string;
    public function minVersion(): string;
    public function detect(): bool;
    public function boot(): void;
    public function health(): array;
    public function abilities(): array;
    public function permissions(): array;
    public function tools(): array;
    public function resources(): array;
    public function shutdown(): void;
}
```

---

## Example Custom Adapter Implementation

```php
namespace WPAIOS\Modules\Integration\Adapters;

class MyCustomFormAdapter extends AbstractPluginAdapter
{
    public function id(): string
    {
        return 'my_custom_form';
    }

    public function name(): string
    {
        return 'My Custom Form Plugin';
    }

    public function detect(): bool
    {
        return class_exists('MyCustomFormPluginClass');
    }
}
```
