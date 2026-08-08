<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\REST;

use WPAIOS\Modules\Builders\BuildersManager;

/**
 * Class BuildersRestController
 * REST API Endpoint `/wp-json/wp-ai-os/v1/builders`.
 */
class BuildersRestController
{
    private string $namespace = 'wp-ai-os/v1';

    public function __construct(private BuildersManager $manager)
    {
    }

    public function registerRoutes(): void
    {
        if (!function_exists('register_rest_route')) {
            return;
        }

        register_rest_route($this->namespace, '/builders', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getBuilders'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/builders/(?P<slug>[a-zA-Z0-9_\-]+)', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getBuilder'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);
    }

    public function checkPermission(): bool
    {
        return function_exists('current_user_can') && current_user_can('manage_options');
    }

    public function getBuilders(\WP_REST_Request $request): \WP_REST_Response
    {
        $report = $this->manager->getRegistry()->detect();
        return new \WP_REST_Response($report, 200);
    }

    public function getBuilder(\WP_REST_Request $request): \WP_REST_Response
    {
        $slug = $request->get_param('slug');
        $adapter = $this->manager->getRegistry()->get($slug);

        if (!$adapter) {
            return new \WP_REST_Response(['error' => 'Builder not found'], 404);
        }

        return new \WP_REST_Response([
            'slug' => $adapter->getSlug(),
            'name' => $adapter->getName(),
            'installed' => $adapter->isInstalled(),
            'active' => $adapter->isActive(),
            'version' => $adapter->getVersion(),
            'capabilities' => $adapter->getCapabilities()->toArray(),
        ], 200);
    }
}
