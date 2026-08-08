# WooCommerce Enterprise Module — WP AI OS

## Architecture & Zero Direct Dependency

The **WooCommerce Enterprise Module** provides a decoupled repository & adapter layer. Business logic in WP AI OS interacts exclusively with standard models (`ProductModel`, `OrderModel`) and contracts (`ProductRepositoryInterface`), remaining completely decoupled from direct WooCommerce class instances.

```
       +---------------------------------------------+
       |   WP AI OS Core & Autonomous Agent Engine   |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |   ProductManager  /  InventoryManager       |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |        ProductRepositoryInterface           |
       +---------------------------------------------+
                              |
       +---------------------------------------------+
       |  ProductRepository (WordPress CPT / Meta)   |
       +---------------------------------------------+
```

---

## Features Supported

1. **Product Management**: Full CRUD for products, prices, SKUs, and post meta.
2. **Inventory Management**: Stock level set, adjust, and stock status updates (`instock`, `outofstock`).
3. **MCP Integration**: Automatic registration of `wp_ai_os_woo_product_manager` and `wp_ai_os_woo_inventory_manager` abilities.
