# WooCommerce Architecture Specification — WP AI OS

## Repository & Service Pattern

1. **Contracts**: `src/Modules/WooCommerce/Contracts/ProductRepositoryInterface.php`
2. **Repositories**: `src/Modules/WooCommerce/Repositories/ProductRepository.php`
3. **Services**: `src/Modules/WooCommerce/Services/ProductManager.php` & `InventoryManager.php`
4. **Facade**: `src/Modules/WooCommerce/WooCommerceManager.php`

All data mutations emit domain events via `EventDispatcherInterface`:
- `woocommerce.product_created`
- `woocommerce.product_updated`
- `woocommerce.product_deleted`
- `woocommerce.inventory_changed`
