<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Services;

use Exception;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\WooCommerce\Contracts\ProductRepositoryInterface;
use WPAIOS\Modules\WooCommerce\Models\ProductModel;

/**
 * Inventory Manager Service handling WooCommerce stock adjustments and low stock alerts.
 */
class InventoryManager
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private LoggerInterface $logger,
        private ?EventDispatcherInterface $eventDispatcher = null
    ) {
    }

    /**
     * Adjust stock quantity for a product.
     *
     * @param int $productId
     * @param int $newQuantity
     * @return ProductModel
     * @throws Exception
     */
    public function setStock(int $productId, int $newQuantity): ProductModel
    {
        $status = $newQuantity > 0 ? 'instock' : 'outofstock';
        $updated = $this->productRepository->update($productId, [
            'stock_quantity' => $newQuantity,
            'stock_status' => $status,
        ]);

        $this->logger->info(sprintf('[InventoryManager] Product ID %d stock updated to %d (%s).', $productId, $newQuantity, $status));
        $this->eventDispatcher?->dispatch('woocommerce.inventory_changed', $productId, $newQuantity, $status);

        return $updated;
    }

    /**
     * Increment or decrement product stock.
     *
     * @param int $productId
     * @param int $delta Can be positive or negative.
     * @return ProductModel
     * @throws Exception
     */
    public function adjustStock(int $productId, int $delta): ProductModel
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new Exception(sprintf('Product ID %d not found.', $productId));
        }

        $current = $product->stockQuantity ?? 0;
        $newStock = max(0, $current + $delta);

        return $this->setStock($productId, $newStock);
    }
}
