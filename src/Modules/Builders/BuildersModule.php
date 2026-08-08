<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Builders\Admin\BuildersAdminDashboard;
use WPAIOS\Modules\Builders\Providers\BuildersServiceProvider;
use WPAIOS\Modules\Builders\REST\BuildersRestController;

/**
 * Class BuildersModule
 * Entry point for Multi-Builder Abstraction Layer.
 */
class BuildersModule
{
    public static function register(Container $container): void
    {
        $provider = new BuildersServiceProvider($container);
        $provider->register();

        if (function_exists('add_action')) {
            add_action('rest_api_init', function () use ($container) {
                $controller = new BuildersRestController($container->get(BuildersManager::class));
                $controller->registerRoutes();
            });

            add_action('admin_menu', function () use ($container) {
                $dashboard = new BuildersAdminDashboard($container->get(BuildersManager::class));
                $dashboard->registerMenu();
            });
        }
    }
}
