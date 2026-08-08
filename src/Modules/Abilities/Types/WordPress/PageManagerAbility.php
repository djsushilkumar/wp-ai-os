<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\WordPress;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Page Manager Ability (Create, Update, Delete WordPress Pages).
 */
class PageManagerAbility extends AbstractAbility
{
    protected string $category   = 'WordPress';
    protected array $permissions = [ 'edit_pages' ];

    public function id(): string
    {
        return 'wp_ai_os_page_manager';
    }

    public function name(): string
    {
        return 'Page Manager';
    }

    public function description(): string
    {
        return 'Create, update, or delete WordPress pages.';
    }

    public function schema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'action'  => [
                    'type' => 'string',
                    'enum' => [ 'create', 'update', 'delete', 'get' ],
                ],
                'page_id' => [ 'type' => 'integer' ],
                'title'   => [ 'type' => 'string' ],
                'content' => [ 'type' => 'string' ],
                'status'  => [
                    'type'    => 'string',
                    'default' => 'publish',
                ],
            ],
            'required'   => [ 'action' ],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'] ?? 'get';

        if ($action === 'create') {
            if (! function_exists('wp_insert_post')) {
                throw new Exception('WordPress functions not loaded.');
            }
            $id = wp_insert_post(
                [
                    'post_type'    => 'page',
                    'post_title'   => $params['title'] ?? 'Untitled Page',
                    'post_content' => $params['content'] ?? '',
                    'post_status'  => $params['status'] ?? 'publish',
                ]
            );

            if (is_wp_error($id)) {
                throw new Exception('Page creation failed: ' . $id->get_error_message());
            }
            return [
                'success' => true,
                'page_id' => $id,
            ];
        }

        if ($action === 'update') {
            $id   = $params['page_id'] ?? 0;
            $args = [ 'ID' => $id ];
            if (isset($params['title'])) {
                $args['post_title'] = $params['title'];
            }
            if (isset($params['content'])) {
                $args['post_content'] = $params['content'];
            }
            if (isset($params['status'])) {
                $args['post_status'] = $params['status'];
            }

            $res = wp_update_post($args);
            if (is_wp_error($res)) {
                throw new Exception('Page update failed: ' . $res->get_error_message());
            }
            return [
                'success' => true,
                'page_id' => $res,
            ];
        }

        if ($action === 'delete') {
            $id  = $params['page_id'] ?? 0;
            $res = wp_delete_post($id, true);
            return [
                'success'    => (bool) $res,
                'deleted_id' => $id,
            ];
        }

        if ($action === 'get') {
            $id   = $params['page_id'] ?? 0;
            $post = get_post($id);
            return $post ? [
                'id'      => $post->ID,
                'title'   => $post->post_title,
                'content' => $post->post_content,
            ] : null;
        }

        throw new Exception('Invalid action specified.');
    }
}
