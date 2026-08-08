<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Services;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\WooCommerce\Contracts\ProductRepositoryInterface;
use WPAIOS\Modules\WooCommerce\Models\ProductModel;

/**
 * Product Manager Service orchestrating WooCommerce Product CRUD operations and events.
 */
class ProductManager
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private LoggerInterface $logger,
        private ?EventDispatcherInterface $eventDispatcher = null
    ) {
    }

    public function getProduct(int $id): ?ProductModel
    {
        return $this->repository->find($id);
    }

    public function createProduct(array $data): ProductModel
    {
        $product = $this->repository->create($data);
        $this->logger->info(sprintf('[ProductManager] Created product [%s] with ID %d.', $product->name, $product->id));
        $this->eventDispatcher?->dispatch('woocommerce.product_created', $product->id, $product->toArray());

        return $product;
    }

    public function updateProduct(int $id, array $data): ProductModel
    {
        $product = $this->repository->update($id, $data);
        $this->logger->info(sprintf('[ProductManager] Updated product ID %d.', $id));
        $this->eventDispatcher?->dispatch('woocommerce.product_updated', $id, $product->toArray());

        return $product;
    }

    public function deleteProduct(int $id, bool $force = false): bool
    {
        $success = $this->repository->delete($id, $force);
        if ($success) {
            $this->logger->info(sprintf('[ProductManager] Deleted product ID %d.', $id));
            $this->eventDispatcher?->dispatch('woocommerce.product_deleted', $id);
        }

        return $success;
    }

    /**
     * @param array<string, mixed> $query
     * @return ProductModel[]
     */
    public function listProducts(array $query = []): array
    {
        return $this->repository->query($query);
    }
}
