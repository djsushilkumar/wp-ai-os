<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Planner;

use WPAIOS\Modules\Automation\Contracts\TaskInterface;

/**
 * Risk Analyzer — evaluates workflow risk level before execution.
 */
class RiskAnalyzer
{
    private const HIGH_RISK_TASKS = [
        'delete_page', 'delete_post', 'delete_user', 'reset_settings',
        'drop_table', 'rollback_database', 'reset_theme', 'install_plugin',
        'activate_plugin', 'deactivate_plugin', 'delete_media',
    ];

    /**
     * Analyze the risk level of a set of tasks.
     *
     * @param TaskInterface[] $tasks
     * @return array{level: string, score: int, high_risk_tasks: string[], warnings: string[]}
     */
    public function analyze(array $tasks): array
    {
        $score = 0;
        $highRiskTasks = [];
        $warnings = [];

        foreach ($tasks as $task) {
            foreach (self::HIGH_RISK_TASKS as $riskKeyword) {
                if (str_contains(strtolower($task->id()), $riskKeyword)) {
                    $score += 30;
                    $highRiskTasks[] = $task->id();
                    $warnings[] = sprintf('Task [%s] is a potentially destructive operation.', $task->id());
                }
            }

            // Tasks with no rollback are higher risk
            if (!$task->isRollbackable()) {
                $score += 5;
            }

            // Tasks with retries indicate unreliable operations
            if ($task->maxRetries() > 3) {
                $score += 10;
                $warnings[] = sprintf('Task [%s] requires more than 3 retries — may be unreliable.', $task->id());
            }
        }

        $level = match (true) {
            $score >= 60 => 'critical',
            $score >= 30 => 'high',
            $score >= 15 => 'medium',
            default => 'low',
        };

        return [
            'level' => $level,
            'score' => $score,
            'high_risk_tasks' => $highRiskTasks,
            'warnings' => $warnings,
        ];
    }
}
