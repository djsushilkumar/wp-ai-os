<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\WooCommerce\Services\ProductManager;

/**
 * WooCommerce Product Manager Ability — exposes product CRUD operations to MCP agents.
 */
class WooCommerceProductAbility extends AbstractAbility
{
    protected string $category = 'WooCommerce';
    protected array $permissions = ['manage_woocommerce'];

    public function __construct(private ProductManager $productManager)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_woo_product_manager';
    }

    public function name(): string
    {
        return 'WooCommerce Product Manager';
    }

    public function description(): string
    {
        return 'Create, update, delete, get, and list WooCommerce products.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['create', 'update', 'delete', 'get', 'list']],
                'product_id' => ['type' => 'integer'],
                'name' => ['type' => 'string'],
                'price' => ['type' => 'number'],
                'regular_price' => ['type' => 'number'],
                'sale_price' => ['type' => 'number'],
                'sku' => ['type' => 'string'],
                'stock_quantity' => ['type' => 'integer'],
                'description' => ['type' => 'string'],
                'short_description' => ['type' => 'string'],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $productId = (int) ($params['product_id'] ?? 0);

        return match ($action) {
            'create' => $this->productManager->createProduct($params)->toArray(),
            'update' => $this->productManager->updateProduct($productId, $params)->toArray(),
            'get' => $this->productManager->getProduct($productId)?->toArray(),
            'delete' => ['success' => $this->productManager->deleteProduct($productId, true)],
            'list' => array_map(fn ($p) => $p->toArray(), $this->productManager->listProducts($params)),
            default => throw new Exception("Unknown WooCommerce product action: {$action}"),
        };
    }
}
