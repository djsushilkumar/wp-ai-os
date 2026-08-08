<?php

declare(strict_types=1);

namespace WPAIOS\Modules\WooCommerce\Providers;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\WooCommerce\Abilities\WooCommerceInventoryAbility;
use WPAIOS\Modules\WooCommerce\Abilities\WooCommerceProductAbility;
use WPAIOS\Modules\WooCommerce\Contracts\ProductRepositoryInterface;
use WPAIOS\Modules\WooCommerce\Repositories\ProductRepository;
use WPAIOS\Modules\WooCommerce\Services\InventoryManager;
use WPAIOS\Modules\WooCommerce\Services\ProductManager;
use WPAIOS\Modules\WooCommerce\WooCommerceManager;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider binding all WooCommerce enterprise services into DI Container.
 */
class WooCommerceServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // 1. Repositories
        $this->container->singleton(ProductRepositoryInterface::class, ProductRepository::class);

        // 2. Services
        $this->container->singleton(ProductManager::class, function () {
            return new ProductManager(
                $this->container->get(ProductRepositoryInterface::class),
                $this->container->get(LoggerInterface::class),
                $this->container->get(EventDispatcherInterface::class)
            );
        });

        $this->container->singleton(InventoryManager::class, function () {
            return new InventoryManager(
                $this->container->get(ProductRepositoryInterface::class),
                $this->container->get(LoggerInterface::class),
                $this->container->get(EventDispatcherInterface::class)
            );
        });

        // 3. Central Manager Facade
        $this->container->singleton(WooCommerceManager::class, function () {
            return new WooCommerceManager(
                $this->container->get(ProductManager::class),
                $this->container->get(InventoryManager::class),
                $this->container->get(LoggerInterface::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var WooCommerceManager $manager */
        $manager = $this->container->get(WooCommerceManager::class);
        $manager->detect();

        // Register Abilities in AbilityRegistry if bound
        if ($this->container->has(AbilityRegistry::class)) {
            /** @var AbilityRegistry $abilityRegistry */
            $abilityRegistry = $this->container->get(AbilityRegistry::class);

            $abilityRegistry->register(new WooCommerceProductAbility(
                $this->container->get(ProductManager::class)
            ));
            $abilityRegistry->register(new WooCommerceInventoryAbility(
                $this->container->get(InventoryManager::class)
            ));
        }
    }
}
