<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Models;

/**
 * Normalized Product Value Object for WooCommerce Products.
 */
class ProductModel
{
    /**
     * @param int $id
     * @param string $name
     * @param string $slug
     * @param string $type 'simple', 'variable', 'grouped', 'external'
     * @param float $price
     * @param float $regularPrice
     * @param float|null $salePrice
     * @param string $sku
     * @param int|null $stockQuantity
     * @param string $stockStatus 'instock', 'outofstock', 'onbackorder'
     * @param string $description
     * @param string $shortDescription
     * @param array<int> $categoryIds
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug = '',
        public readonly string $type = 'simple',
        public readonly float $price = 0.0,
        public readonly float $regularPrice = 0.0,
        public readonly ?float $salePrice = null,
        public readonly string $sku = '',
        public readonly ?int $stockQuantity = null,
        public readonly string $stockStatus = 'instock',
        public readonly string $description = '',
        public readonly string $shortDescription = '',
        public readonly array $categoryIds = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'price' => $this->price,
            'regular_price' => $this->regularPrice,
            'sale_price' => $this->salePrice,
            'sku' => $this->sku,
            'stock_quantity' => $this->stockQuantity,
            'stock_status' => $this->stockStatus,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'category_ids' => $this->categoryIds,
        ];
    }
}
