<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Repositories;

use Exception;
use WPAIOS\Modules\WooCommerce\Contracts\ProductRepositoryInterface;
use WPAIOS\Modules\WooCommerce\Models\ProductModel;

/**
 * Product Repository — handles WordPress custom post type 'product' and post meta persistence.
 */
class ProductRepository implements ProductRepositoryInterface
{
    public function find(int $id): ?ProductModel
    {
        $post = get_post($id);
        if (!$post || $post->post_type !== 'product') {
            return null;
        }

        $regularPrice = (float) get_post_meta($id, '_regular_price', true);
        $salePriceRaw = get_post_meta($id, '_sale_price', true);
        $salePrice = ($salePriceRaw !== '' && $salePriceRaw !== false) ? (float) $salePriceRaw : null;
        $price = (float) get_post_meta($id, '_price', true);
        $sku = (string) get_post_meta($id, '_sku', true);
        $stockRaw = get_post_meta($id, '_stock', true);
        $stock = ($stockRaw !== '' && $stockRaw !== false) ? (int) $stockRaw : null;
        $stockStatus = (string) get_post_meta($id, '_stock_status', true) ?: 'instock';

        return new ProductModel(
            id: $id,
            name: $post->post_title,
            slug: $post->post_name,
            price: $price > 0 ? $price : $regularPrice,
            regularPrice: $regularPrice,
            salePrice: $salePrice,
            sku: $sku,
            stockQuantity: $stock,
            stockStatus: $stockStatus,
            description: $post->post_content,
            shortDescription: $post->post_excerpt
        );
    }

    public function create(array $data): ProductModel
    {
        if (!function_exists('wp_insert_post')) {
            throw new Exception('WordPress functions unavailable.');
        }

        $postId = wp_insert_post([
            'post_title' => sanitize_text_field($data['name'] ?? 'New Product'),
            'post_type' => 'product',
            'post_status' => sanitize_key($data['status'] ?? 'publish'),
            'post_content' => wp_kses_post($data['description'] ?? ''),
            'post_excerpt' => wp_kses_post($data['short_description'] ?? ''),
        ]);

        if (is_wp_error($postId)) {
            throw new Exception('Product creation error: ' . $postId->get_error_message());
        }

        $regularPrice = (float) ($data['regular_price'] ?? $data['price'] ?? 0.0);
        $salePrice = isset($data['sale_price']) ? (float) $data['sale_price'] : null;
        $activePrice = $salePrice !== null ? $salePrice : $regularPrice;
        $sku = sanitize_text_field($data['sku'] ?? '');
        $stock = isset($data['stock_quantity']) ? (int) $data['stock_quantity'] : null;
        $stockStatus = sanitize_key($data['stock_status'] ?? 'instock');

        update_post_meta($postId, '_regular_price', (string) $regularPrice);
        if ($salePrice !== null) {
            update_post_meta($postId, '_sale_price', (string) $salePrice);
        }
        update_post_meta($postId, '_price', (string) $activePrice);
        update_post_meta($postId, '_sku', $sku);
        if ($stock !== null) {
            update_post_meta($postId, '_stock', (string) $stock);
            update_post_meta($postId, '_manage_stock', 'yes');
        }
        update_post_meta($postId, '_stock_status', $stockStatus);

        return $this->find($postId);
    }

    public function update(int $id, array $data): ProductModel
    {
        $post = get_post($id);
        if (!$post || $post->post_type !== 'product') {
            throw new Exception(sprintf('Product ID %d not found.', $id));
        }

        $updateArgs = ['ID' => $id];
        if (isset($data['name'])) {
            $updateArgs['post_title'] = sanitize_text_field($data['name']);
        }
        if (isset($data['description'])) {
            $updateArgs['post_content'] = wp_kses_post($data['description']);
        }
        if (isset($data['short_description'])) {
            $updateArgs['post_excerpt'] = wp_kses_post($data['short_description']);
        }

        wp_update_post($updateArgs);

        if (isset($data['regular_price'])) {
            update_post_meta($id, '_regular_price', (string) ((float) $data['regular_price']));
            update_post_meta($id, '_price', (string) ((float) $data['regular_price']));
        }
        if (isset($data['sale_price'])) {
            update_post_meta($id, '_sale_price', (string) ((float) $data['sale_price']));
            update_post_meta($id, '_price', (string) ((float) $data['sale_price']));
        }
        if (isset($data['sku'])) {
            update_post_meta($id, '_sku', sanitize_text_field($data['sku']));
        }
        if (isset($data['stock_quantity'])) {
            update_post_meta($id, '_stock', (string) ((int) $data['stock_quantity']));
            update_post_meta($id, '_manage_stock', 'yes');
        }
        if (isset($data['stock_status'])) {
            update_post_meta($id, '_stock_status', sanitize_key($data['stock_status']));
        }

        return $this->find($id);
    }

    public function delete(int $id, bool $force = false): bool
    {
        $result = wp_delete_post($id, $force);
        return (bool) $result;
    }

    public function query(array $query): array
    {
        $posts = get_posts([
            'post_type' => 'product',
            'numberposts' => $query['limit'] ?? 10,
            'offset' => $query['offset'] ?? 0,
        ]);

        $products = [];
        foreach ($posts as $post) {
            $p = $this->find($post->ID);
            if ($p) {
                $products[] = $p;
            }
        }

        return $products;
    }
}
