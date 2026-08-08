<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Knowledge\Admin\KnowledgeAdminDashboard;
use WPAIOS\Modules\Knowledge\Providers\KnowledgeServiceProvider;
use WPAIOS\Modules\Knowledge\REST\KnowledgeRestController;

/**
 * Class KnowledgeModule
 * Module entry point for Knowledge Base & RAG Platform.
 */
class KnowledgeModule
{
    public static function register(Container $container): void
    {
        $provider = new KnowledgeServiceProvider($container);
        $provider->register();

        if (function_exists('add_action')) {
            add_action('rest_api_init', function () use ($container) {
                $controller = new KnowledgeRestController($container->get(KnowledgeManager::class));
                $controller->registerRoutes();
            });

            add_action('admin_menu', function () use ($container) {
                $dashboard = new KnowledgeAdminDashboard($container->get(KnowledgeManager::class));
                $dashboard->registerMenu();
            });
        }
    }
}
