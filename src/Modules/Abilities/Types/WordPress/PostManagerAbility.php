<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\WordPress;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Post Manager Ability (Create, Update, Delete WordPress Posts & CPTs).
 */
class PostManagerAbility extends AbstractAbility
{
    protected string $category   = 'WordPress';
    protected array $permissions = [ 'edit_posts' ];

    public function id(): string
    {
        return 'wp_ai_os_post_manager';
    }

    public function name(): string
    {
        return 'Post Manager';
    }

    public function description(): string
    {
        return 'Create, update, delete, or fetch WordPress posts and custom post types.';
    }

    public function schema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'action'    => [
                    'type' => 'string',
                    'enum' => [ 'create', 'update', 'delete', 'get', 'list' ],
                ],
                'post_id'   => [ 'type' => 'integer' ],
                'post_type' => [
                    'type'    => 'string',
                    'default' => 'post',
                ],
                'title'     => [ 'type' => 'string' ],
                'content'   => [ 'type' => 'string' ],
                'status'    => [
                    'type'    => 'string',
                    'default' => 'publish',
                ],
            ],
            'required'   => [ 'action' ],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'] ?? 'list';

        if ($action === 'create') {
            $id = wp_insert_post(
                [
                    'post_type'    => sanitize_key($params['post_type'] ?? 'post'),
                    'post_title'   => sanitize_text_field($params['title'] ?? 'Untitled Post'),
                    'post_content' => wp_kses_post($params['content'] ?? ''),
                    'post_status'  => sanitize_key($params['status'] ?? 'publish'),
                ]
            );
            if (is_wp_error($id)) {
                throw new Exception('Post creation error: ' . $id->get_error_message());
            }
            return [
                'success' => true,
                'post_id' => $id,
            ];
        }

        if ($action === 'update') {
            $updateArgs = [ 'ID' => (int) ($params['post_id'] ?? 0) ];
            if (isset($params['title'])) {
                $updateArgs['post_title'] = sanitize_text_field($params['title']);
            }
            if (isset($params['content'])) {
                $updateArgs['post_content'] = wp_kses_post($params['content']);
            }
            if (isset($params['status'])) {
                $updateArgs['post_status'] = sanitize_key($params['status']);
            }

            $res = wp_update_post($updateArgs);
            if (is_wp_error($res)) {
                throw new Exception('Post update error: ' . $res->get_error_message());
            }
            return [
                'success' => true,
                'post_id' => $res,
            ];
        }

        if ($action === 'delete') {
            $postId = (int) ($params['post_id'] ?? 0);
            $res    = wp_delete_post($postId, true);
            return [ 'success' => (bool) $res ];
        }

        if ($action === 'list') {
            $posts = get_posts(
                [
                    'post_type'   => sanitize_key($params['post_type'] ?? 'post'),
                    'numberposts' => 10,
                ]
            );
            return array_map(
                fn ($p) => [
                    'id'    => $p->ID,
                    'title' => $p->post_title,
                    'slug'  => $p->post_name,
                ],
                $posts
            );
        }

        return null;
    }
}
