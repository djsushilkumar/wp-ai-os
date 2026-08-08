<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Contracts;

use WPAIOS\Modules\WooCommerce\Models\ProductModel;

/**
 * Product Repository Interface — contract for WooCommerce product operations.
 */
interface ProductRepositoryInterface
{
    public function find(int $id): ?ProductModel;

    /**
     * @param array<string, mixed> $data
     * @return ProductModel
     */
    public function create(array $data): ProductModel;

    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return ProductModel
     */
    public function update(int $id, array $data): ProductModel;

    public function delete(int $id, bool $force = false): bool;

    /**
     * @param array<string, mixed> $query
     * @return ProductModel[]
     */
    public function query(array $query): array;
}
