<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Memory;

use WPAIOS\Modules\Automation\Models\WorkflowContext;

/**
 * Checkpoint Memory using custom MySQL table ($wpdb->prefix . 'wp_ai_os_checkpoints').
 */
class CheckpointMemory
{
    /**
     * Save a checkpoint snapshot of the workflow context.
     *
     * @param WorkflowContext $context
     * @param string $checkpointLabel e.g. 'after_task_3'
     */
    public function save(WorkflowContext $context, string $checkpointLabel = 'auto'): void
    {
        $payload = [
            'completed_tasks' => $context->getCompletedTaskIds(),
            'context_data' => $context->all(),
        ];

        global $wpdb;
        if (isset($wpdb) && !empty($wpdb->prefix)) {
            $table = $wpdb->prefix . 'wp_ai_os_checkpoints';
            $wpdb->replace(
                $table,
                [
                    'run_id' => $context->runId,
                    'workflow_id' => $context->workflowId,
                    'label' => $checkpointLabel,
                    'saved_at' => time(),
                    'snapshot_data' => wp_json_encode($payload),
                ],
                ['%s', '%s', '%s', '%d', '%s']
            );
        }
    }

    /**
     * Load a checkpoint by run ID.
     *
     * @param string $runId
     * @return array<string, mixed>|null
     */
    public function load(string $runId): ?array
    {
        global $wpdb;
        if (!isset($wpdb) || empty($wpdb->prefix)) {
            return null;
        }

        $table = $wpdb->prefix . 'wp_ai_os_checkpoints';
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE run_id = %s", $runId));

        if (!$row) {
            return null;
        }

        $decoded = json_decode($row->snapshot_data, true);
        return [
            'run_id' => $row->run_id,
            'workflow_id' => $row->workflow_id,
            'label' => $row->label,
            'saved_at' => (int) $row->saved_at,
            'snapshot_data' => is_array($decoded) ? $decoded : [],
        ];
    }

    /**
     * Delete a checkpoint after successful completion.
     *
     * @param string $runId
     */
    public function clear(string $runId): void
    {
        global $wpdb;
        if (isset($wpdb) && !empty($wpdb->prefix)) {
            $table = $wpdb->prefix . 'wp_ai_os_checkpoints';
            $wpdb->delete($table, ['run_id' => $runId], ['%s']);
        }
    }
}
