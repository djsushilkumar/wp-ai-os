<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\Automation;

use WPAIOS\Modules\Abilities\AbstractAbility;

/**
 * Cron Manager Ability (Inspect & Trigger WP-Cron jobs).
 */
class CronAbility extends AbstractAbility
{
    protected string $category   = 'Automation';
    protected array $permissions = [ 'manage_options' ];

    public function id(): string
    {
        return 'wp_ai_os_cron_manager';
    }

    public function name(): string
    {
        return 'Cron Manager';
    }

    public function description(): string
    {
        return 'Inspect scheduled WP-Cron jobs or trigger scheduled event hooks.';
    }

    public function schema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'action'    => [
                    'type' => 'string',
                    'enum' => [ 'list', 'trigger' ],
                ],
                'hook_name' => [ 'type' => 'string' ],
            ],
            'required'   => [ 'action' ],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'] ?? 'list';

        if ($action === 'trigger' && ! empty($params['hook_name'])) {
            if (function_exists('do_action')) {
                do_action($params['hook_name']);
                return [
                    'triggered' => true,
                    'hook'      => $params['hook_name'],
                ];
            }
        }

        if (function_exists('_get_cron_array')) {
            $crons   = _get_cron_array();
            $summary = [];
            if (is_array($crons)) {
                foreach ($crons as $timestamp => $hooks) {
                    foreach ($hooks as $hook => $data) {
                        $summary[] = [
                            'timestamp' => $timestamp,
                            'time'      => date('Y-m-d H:i:s', $timestamp),
                            'hook'      => $hook,
                        ];
                    }
                }
            }
            return [
                'total_scheduled' => count($summary),
                'jobs'            => array_slice($summary, 0, 20),
            ];
        }

        return [
            'total_scheduled' => 0,
            'jobs'            => [],
        ];
    }
}
