<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Queue;

/**
 * Persistent Priority Workflow Queue using custom MySQL table ($wpdb->prefix . 'wp_ai_os_workflow_queue').
 */
class WorkflowQueue
{
    /**
     * Enqueue a new workflow for execution.
     *
     * @param string $workflowId
     * @param array<string, mixed> $input
     * @param int $priority    Lower = higher priority
     * @param int $maxAttempts
     * @param int|null $runAfter Unix timestamp to delay execution
     * @return QueueItem
     */
    public function enqueue(
        string $workflowId,
        array $input = [],
        int $priority = 10,
        int $maxAttempts = 3,
        ?int $runAfter = null
    ): QueueItem {
        $item = new QueueItem($workflowId, $input, $priority, $maxAttempts, $runAfter);

        global $wpdb;
        if (isset($wpdb) && !empty($wpdb->prefix)) {
            $table = $wpdb->prefix . 'wp_ai_os_workflow_queue';
            $wpdb->insert(
                $table,
                [
                    'id' => $item->id,
                    'workflow_id' => $item->workflowId,
                    'input' => wp_json_encode($item->input),
                    'priority' => $item->priority,
                    'status' => $item->status,
                    'attempts' => $item->attempts,
                    'max_attempts' => $item->maxAttempts,
                    'run_after' => $item->runAfter,
                    'created_at' => $item->createdAt,
                ],
                ['%s', '%s', '%s', '%d', '%s', '%d', '%d', '%d', '%d']
            );
        }

        return $item;
    }

    /**
     * Dequeue the next ready item (ordered by priority ASC, then createdAt ASC).
     *
     * @return QueueItem|null
     */
    public function dequeue(): ?QueueItem
    {
        global $wpdb;
        if (!isset($wpdb) || empty($wpdb->prefix)) {
            return null;
        }

        $table = $wpdb->prefix . 'wp_ai_os_workflow_queue';
        $now = time();

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$table} WHERE status = 'pending' AND (run_after IS NULL OR run_after <= %d) ORDER BY priority ASC, created_at ASC LIMIT 1",
                $now
            )
        );

        if (!$row) {
            return null;
        }

        $item = new QueueItem($row->workflow_id, json_decode($row->input, true) ?: [], (int) $row->priority, (int) $row->max_attempts, $row->run_after ? (int) $row->run_after : null);
        $item->id = $row->id;
        $item->attempts = ((int) $row->attempts) + 1;
        $item->status = 'processing';

        $wpdb->update(
            $table,
            ['status' => 'processing', 'attempts' => $item->attempts],
            ['id' => $item->id],
            ['%s', '%d'],
            ['%s']
        );

        return $item;
    }

    /**
     * Mark an item as completed and remove from queue.
     *
     * @param string $itemId
     */
    public function complete(string $itemId): void
    {
        global $wpdb;
        if (isset($wpdb) && !empty($wpdb->prefix)) {
            $table = $wpdb->prefix . 'wp_ai_os_workflow_queue';
            $wpdb->update($table, ['status' => 'completed'], ['id' => $itemId], ['%s'], ['%s']);
        }
    }

    /**
     * Mark an item as failed. If retries remain, reset to pending with delay.
     *
     * @param string $itemId
     * @param string $error
     */
    public function fail(string $itemId, string $error): void
    {
        global $wpdb;
        if (!isset($wpdb) || empty($wpdb->prefix)) {
            return;
        }

        $table = $wpdb->prefix . 'wp_ai_os_workflow_queue';
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE id = %s", $itemId));
        if (!$row) {
            return;
        }

        $attempts = (int) $row->attempts;
        $maxAttempts = (int) $row->max_attempts;

        if ($attempts < $maxAttempts) {
            $runAfter = time() + (30 * (2 ** ($attempts - 1)));
            $wpdb->update(
                $table,
                ['status' => 'pending', 'error' => $error, 'run_after' => $runAfter],
                ['id' => $itemId],
                ['%s', '%s', '%d'],
                ['%s']
            );
        } else {
            $wpdb->update(
                $table,
                ['status' => 'failed', 'error' => $error],
                ['id' => $itemId],
                ['%s', '%s'],
                ['%s']
            );
        }
    }

    /**
     * Cancel a pending queue item.
     *
     * @param string $itemId
     */
    public function cancel(string $itemId): void
    {
        global $wpdb;
        if (isset($wpdb) && !empty($wpdb->prefix)) {
            $table = $wpdb->prefix . 'wp_ai_os_workflow_queue';
            $wpdb->update($table, ['status' => 'cancelled'], ['id' => $itemId], ['%s'], ['%s']);
        }
    }
}
