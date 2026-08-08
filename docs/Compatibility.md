# Compatibility Matrix — WP AI OS Plugin Integrations

## Supported Plugin Targets

| Plugin / Framework | Adapter ID | Class | Detection Strategy |
| :--- | :--- | :--- | :--- |
| **Elementor** | `elementor` | `ElementorAdapter` | `ELEMENTOR_VERSION` constant |
| **WooCommerce** | `woocommerce` | `WooCommerceAdapter` | `WooCommerce` class |
| **Gutenberg** | `gutenberg` | `GutenbergAdapter` | `register_block_type` function |
| **Rank Math SEO** | `rankmath` | `RankMathAdapter` | `RankMath` class |
| **Yoast SEO** | `yoast` | `YoastAdapter` | `WPSEO_VERSION` constant |
| **ACF** | `acf` | `AcfAdapter` | `get_field` function / `ACF` class |
| **Fluent Forms** | `fluent_forms` | `FluentFormsAdapter` | `FLUENTFORM_VERSION` constant |
