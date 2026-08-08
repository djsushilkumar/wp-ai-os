<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Models;

/**
 * Normalized Order Value Object for WooCommerce Orders.
 */
class OrderModel
{
    /**
     * @param int $id
     * @param string $status 'pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed'
     * @param float $total
     * @param string $currency
     * @param int $customerId
     * @param array<array<string, mixed>> $lineItems
     * @param int|null $createdAt Timestamp
     */
    public function __construct(
        public readonly int $id,
        public readonly string $status,
        public readonly float $total = 0.0,
        public readonly string $currency = 'USD',
        public readonly int $customerId = 0,
        public readonly array $lineItems = [],
        public readonly ?int $createdAt = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'currency' => $this->currency,
            'customer_id' => $this->customerId,
            'line_items' => $this->lineItems,
            'created_at' => $this->createdAt,
        ];
    }
}
