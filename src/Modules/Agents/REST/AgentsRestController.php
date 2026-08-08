<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\REST;

use WPAIOS\Modules\Agents\AgentsManager;

/**
 * Class AgentsRestController
 * REST API Endpoint `/wp-json/wp-ai-os/v1/agents`.
 */
class AgentsRestController
{
    private string $namespace = 'wp-ai-os/v1';

    public function __construct(private AgentsManager $manager)
    {
    }

    public function registerRoutes(): void
    {
        if (!function_exists('register_rest_route')) {
            return;
        }

        register_rest_route($this->namespace, '/agents', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getAgents'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/agents/approvals', [
            [
                'methods' => 'GET',
                'callback' => [$this, 'getApprovals'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);

        register_rest_route($this->namespace, '/agents/approvals/(?P<id>[a-zA-Z0-9_\-]+)/approve', [
            [
                'methods' => 'POST',
                'callback' => [$this, 'approveRequest'],
                'permission_callback' => [$this, 'checkPermission'],
            ],
        ]);
    }

    public function checkPermission(): bool
    {
        return function_exists('current_user_can') && current_user_can('manage_options');
    }

    public function getAgents(\WP_REST_Request $request): \WP_REST_Response
    {
        $list = $this->manager->getRegistry()->listSummary();
        return new \WP_REST_Response($list, 200);
    }

    public function getApprovals(\WP_REST_Request $request): \WP_REST_Response
    {
        $pending = $this->manager->getApprovalManager()->getPendingApprovals();
        return new \WP_REST_Response($pending, 200);
    }

    public function approveRequest(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = $request->get_param('id');
        $user = function_exists('wp_get_current_user') ? wp_get_current_user()->user_login : 'admin';
        $success = $this->manager->getApprovalManager()->approve($id, $user, 'Approved via REST API');

        if (!$success) {
            return new \WP_REST_Response(['error' => 'Approval request not found or already processed'], 404);
        }

        return new \WP_REST_Response(['success' => true, 'approval_id' => $id], 200);
    }
}
