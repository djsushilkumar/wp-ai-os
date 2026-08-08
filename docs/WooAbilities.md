# WooCommerce MCP Abilities Specification — WP AI OS

## 1. Product Manager Ability (`wp_ai_os_woo_product_manager`)

### Actions Supported
- `create`: Create new product (name, price, regular_price, sale_price, sku, stock_quantity, description)
- `update`: Update existing product by ID
- `get`: Retrieve product details by ID
- `delete`: Delete product permanently by ID
- `list`: List products with pagination

---

## 2. Inventory Manager Ability (`wp_ai_os_woo_inventory_manager`)

### Actions Supported
- `set`: Explicitly set stock quantity and stock status
- `adjust`: Increment or decrement stock quantity by delta offset
