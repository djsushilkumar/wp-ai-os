<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Contracts;

use WPAIOS\Modules\WooCommerce\Models\OrderModel;

/**
 * Order Repository Interface — contract for WooCommerce order operations.
 */
interface OrderRepositoryInterface
{
    public function find(int $id): ?OrderModel;

    /**
     * @param array<string, mixed> $data
     * @return OrderModel
     */
    public function create(array $data): OrderModel;

    public function updateStatus(int $id, string $status): bool;

    /**
     * @param array<string, mixed> $query
     * @return OrderModel[]
     */
    public function query(array $query): array;
}
