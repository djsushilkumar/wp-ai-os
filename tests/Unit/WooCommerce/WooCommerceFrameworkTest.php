<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\WooCommerce;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\WooCommerce\Models\ProductModel;
use WPAIOS\Modules\WooCommerce\Repositories\ProductRepository;
use WPAIOS\Modules\WooCommerce\Services\ProductManager;

class WooCommerceFrameworkTest extends TestCase
{
    public function testProductModelSerialization(): void
    {
        $product = new ProductModel(
            id: 101,
            name: 'Test Product',
            price: 49.99,
            regularPrice: 49.99,
            sku: 'TEST-SKU-101',
            stockQuantity: 50,
            stockStatus: 'instock'
        );

        $array = $product->toArray();

        $this->assertEquals(101, $array['id']);
        $this->assertEquals('Test Product', $array['name']);
        $this->assertEquals(49.99, $array['price']);
        $this->assertEquals('TEST-SKU-101', $array['sku']);
        $this->assertEquals(50, $array['stock_quantity']);
    }

    public function testProductManagerCreatesProductModel(): void
    {
        $repository = $this->createMock(ProductRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $productModel = new ProductModel(id: 1, name: 'Mock Product', price: 19.99);

        $repository->expects($this->once())
            ->method('create')
            ->willReturn($productModel);

        $manager = new ProductManager($repository, $logger);
        $result = $manager->createProduct(['name' => 'Mock Product', 'price' => 19.99]);

        $this->assertEquals(1, $result->id);
        $this->assertEquals('Mock Product', $result->name);
    }
}
