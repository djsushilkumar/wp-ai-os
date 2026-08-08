# WooCommerce Code Examples — WP AI OS

## PHP Service Usage

```php
use WPAIOS\Modules\WooCommerce\Services\ProductManager;
use WPAIOS\Modules\WooCommerce\Services\InventoryManager;

$productManager = $container->get(ProductManager::class);
$inventoryManager = $container->get(InventoryManager::class);

// Create a Product
$product = $productManager->createProduct([
    'name' => 'AI E-Book Guide',
    'regular_price' => 29.99,
    'sku' => 'AI-GUIDE-001',
    'stock_quantity' => 100,
]);

// Adjust Stock
$inventoryManager->adjustStock($product->id, -1);
```

---

## MCP Ability Tool Call

```json
{
    "tool": "wp_ai_os_woo_product_manager",
    "arguments": {
        "action": "create",
        "name": "Enterprise AI Plugin Subscription",
        "price": 199.00,
        "sku": "AI-SUB-ENT"
    }
}
```
