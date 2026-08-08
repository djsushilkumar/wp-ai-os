<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Forms\Admin\FormsAdminDashboard;
use WPAIOS\Modules\Forms\Providers\FormsServiceProvider;
use WPAIOS\Modules\Forms\REST\FormsRestController;

/**
 * Class FormsModule
 * Main module bootstrapper for Forms.
 */
class FormsModule
{
    public static function register(Container $container): void
    {
        $provider = new FormsServiceProvider($container);
        $provider->register();

        if (function_exists('add_action')) {
            add_action('rest_api_init', function () use ($container) {
                $controller = new FormsRestController($container->get(FormsManager::class));
                $controller->registerRoutes();
            });

            add_action('admin_menu', function () use ($container) {
                $dashboard = new FormsAdminDashboard($container->get(FormsManager::class));
                $dashboard->registerMenu();
            });
        }
    }
}
