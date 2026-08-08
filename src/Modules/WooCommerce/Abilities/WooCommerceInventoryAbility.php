<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\WooCommerce\Services\InventoryManager;

/**
 * WooCommerce Inventory Manager Ability — exposes stock control to MCP agents.
 */
class WooCommerceInventoryAbility extends AbstractAbility
{
    protected string $category = 'WooCommerce';
    protected array $permissions = ['manage_woocommerce'];

    public function __construct(private InventoryManager $inventoryManager)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_woo_inventory_manager';
    }

    public function name(): string
    {
        return 'WooCommerce Inventory Manager';
    }

    public function description(): string
    {
        return 'Set or adjust stock quantities for WooCommerce products.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['set', 'adjust']],
                'product_id' => ['type' => 'integer'],
                'quantity' => ['type' => 'integer', 'description' => 'Target stock quantity (for set action).'],
                'delta' => ['type' => 'integer', 'description' => 'Quantity change offset (positive or negative, for adjust action).'],
            ],
            'required' => ['action', 'product_id'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $productId = (int) ($params['product_id'] ?? 0);

        return match ($action) {
            'set' => $this->inventoryManager->setStock($productId, (int) ($params['quantity'] ?? 0))->toArray(),
            'adjust' => $this->inventoryManager->adjustStock($productId, (int) ($params['delta'] ?? 0))->toArray(),
            default => throw new Exception("Unknown WooCommerce inventory action: {$action}"),
        };
    }
}
