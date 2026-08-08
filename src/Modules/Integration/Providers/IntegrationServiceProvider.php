<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Providers;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Integration\Abilities\IntegrationDiscoveryAbility;
use WPAIOS\Modules\Integration\Adapters\AcfAdapter;
use WPAIOS\Modules\Integration\Adapters\ElementorAdapter;
use WPAIOS\Modules\Integration\Adapters\FluentFormsAdapter;
use WPAIOS\Modules\Integration\Adapters\GutenbergAdapter;
use WPAIOS\Modules\Integration\Adapters\RankMathAdapter;
use WPAIOS\Modules\Integration\Adapters\WooCommerceAdapter;
use WPAIOS\Modules\Integration\Adapters\YoastAdapter;
use WPAIOS\Modules\Integration\Compatibility\CompatibilityChecker;
use WPAIOS\Modules\Integration\Discovery\PluginDiscoveryManager;
use WPAIOS\Modules\Integration\Registry\IntegrationRegistry;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider registering universal plugin adapters into DI Container.
 */
class IntegrationServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(IntegrationRegistry::class);
        $this->container->singleton(CompatibilityChecker::class);
        $this->container->singleton(PluginDiscoveryManager::class, function () {
            return new PluginDiscoveryManager(
                $this->container->get(IntegrationRegistry::class),
                $this->container->get(LoggerInterface::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var IntegrationRegistry $registry */
        $registry = $this->container->get(IntegrationRegistry::class);

        // Register default plugin adapters
        $registry->register(new ElementorAdapter());
        $registry->register(new WooCommerceAdapter());
        $registry->register(new GutenbergAdapter());
        $registry->register(new RankMathAdapter());
        $registry->register(new YoastAdapter());
        $registry->register(new AcfAdapter());
        $registry->register(new FluentFormsAdapter());

        // Sync discovery ability to AbilityRegistry
        if ($this->container->has(AbilityRegistry::class)) {
            /** @var AbilityRegistry $abilityRegistry */
            $abilityRegistry = $this->container->get(AbilityRegistry::class);
            $abilityRegistry->register(new IntegrationDiscoveryAbility(
                $this->container->get(PluginDiscoveryManager::class)
            ));
        }

        // Trigger discovery scan
        /** @var PluginDiscoveryManager $discoveryManager */
        $discoveryManager = $this->container->get(PluginDiscoveryManager::class);
        $discoveryManager->discover();
    }
}
