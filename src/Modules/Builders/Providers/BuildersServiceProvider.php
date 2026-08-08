<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Providers;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Builders\Adapters\BricksAdapter;
use WPAIOS\Modules\Builders\Adapters\DiviAdapter;
use WPAIOS\Modules\Builders\Adapters\ElementorAdapter;
use WPAIOS\Modules\Builders\Adapters\GutenbergAdapter;
use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Builders\Discovery\BuilderDiscovery;
use WPAIOS\Modules\Builders\Registry\BuilderRegistry;
use WPAIOS\Modules\Elementor\ElementorManager;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Class BuildersServiceProvider
 * Binds Multi-Builder dependencies into DI container.
 */
class BuildersServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(BuilderRegistry::class, function (Container $c) {
            $registry = new BuilderRegistry();

            $elementorManager = $c->has(ElementorManager::class) ? $c->get(ElementorManager::class) : null;

            $registry->register(new ElementorAdapter($elementorManager));
            $registry->register(new GutenbergAdapter());
            $registry->register(new BricksAdapter());
            $registry->register(new DiviAdapter());

            return $registry;
        });

        $this->container->singleton(BuilderDiscovery::class, function (Container $c) {
            $registry = $c->get(BuilderRegistry::class);
            return new BuilderDiscovery($registry->all());
        });

        $this->container->singleton(BuildersManager::class, function (Container $c) {
            return new BuildersManager(
                $c->get(BuilderRegistry::class),
                $c->get(BuilderDiscovery::class)
            );
        });
    }

    public function boot(): void
    {
        // Lazy-loaded builder initialization hook
    }
}
