<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Agents\Admin\AgentsAdminDashboard;
use WPAIOS\Modules\Agents\Providers\AgentsServiceProvider;
use WPAIOS\Modules\Agents\REST\AgentsRestController;

/**
 * Class AgentsModule
 * Module entry point for Multi-Agent Orchestration System.
 */
class AgentsModule
{
    public static function register(Container $container): void
    {
        $provider = new AgentsServiceProvider($container);
        $provider->register();

        if (function_exists('add_action')) {
            add_action(
                'rest_api_init',
                function () use ($container) {
                    $controller = new AgentsRestController($container->get(AgentsManager::class));
                    $controller->registerRoutes();
                }
            );

            add_action(
                'admin_menu',
                function () use ($container) {
                    $dashboard = new AgentsAdminDashboard($container->get(AgentsManager::class));
                    $dashboard->registerMenu();
                }
            );
        }
    }
}
