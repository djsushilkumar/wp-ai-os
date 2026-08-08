<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\REST;

use WPAIOS\Modules\Forms\FormsManager;

/**
 * Class FormsRestController
 * REST API Endpoint `/wp-json/wp-ai-os/v1/forms`.
 */
class FormsRestController
{
    private string $namespace = 'wp-ai-os/v1';

    public function __construct(private FormsManager $manager)
    {
    }

    public function registerRoutes(): void
    {
        if (!function_exists('register_rest_route')) {
            return;
        }

        register_rest_route($this->namespace, '/forms', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getForms'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'createForm'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/forms/(?P<id>[a-zA-Z0-9_\-]+)', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getForm'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'deleteForm'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/forms/providers', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getProviders'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);
    }

    public function checkPermission(): bool
    {
        return function_exists('current_user_can') && current_user_can('manage_options');
    }

    public function getForms(\WP_REST_Request $request): \WP_REST_Response
    {
        $provider = $request->get_param('provider');
        $forms = $this->manager->getRepository()->findAll($provider);
        $data = array_map(fn ($f) => $f->toArray(), $forms);
        return new \WP_REST_Response($data, 200);
    }

    public function getForm(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = $request->get_param('id');
        $form = $this->manager->getRepository()->findById($id);

        if (!$form) {
            return new \WP_REST_Response(['error' => 'Form not found'], 404);
        }

        return new \WP_REST_Response($form->toArray(), 200);
    }

    public function createForm(\WP_REST_Request $request): \WP_REST_Response
    {
        $params = $request->get_json_params() ?? [];
        if (empty($params['title'])) {
            return new \WP_REST_Response(['error' => 'Form title is required'], 400);
        }

        $form = \WPAIOS\Modules\Forms\Services\FormFactory::createForm($params);
        $saved = $this->manager->getRepository()->save($form);
        return new \WP_REST_Response($saved->toArray(), 201);
    }

    public function deleteForm(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = $request->get_param('id');
        $success = $this->manager->getRepository()->delete($id);

        if (!$success) {
            return new \WP_REST_Response(['error' => 'Failed to delete form or form not found'], 404);
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function getProviders(\WP_REST_Request $request): \WP_REST_Response
    {
        $report = $this->manager->getDiscovery()->discoverProviders();
        return new \WP_REST_Response($report, 200);
    }
}
