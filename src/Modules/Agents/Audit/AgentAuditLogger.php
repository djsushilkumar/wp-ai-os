<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Audit;

/**
 * Class AgentAuditLogger
 * Immutable audit trail logger for agent actions with secret isolation.
 */
class AgentAuditLogger
{
    private array $logs = [];

    public function log(
        string $agentId,
        string $taskId,
        string $abilityName,
        array $params,
        string $status,
        float $executionTime,
        ?string $error = null
    ): array {
        // Redact secrets
        $sanitizedParams = $this->redactSecrets($params);

        $entry = [
            'id'                => 'log_' . uniqid(),
            'agent_id'          => $agentId,
            'task_id'           => $taskId,
            'ability'           => $abilityName,
            'params_hash'       => md5(json_encode($sanitizedParams)),
            'status'            => $status,
            'execution_time_ms' => round($executionTime * 1000, 2),
            'error'             => $error,
            'timestamp'         => gmdate('Y-m-d H:i:s'),
        ];

        $this->logs[] = $entry;
        return $entry;
    }

    private function redactSecrets(array $params): array
    {
        $sensitiveKeys = [ 'password', 'secret', 'key', 'token', 'api_key', 'authorization' ];
        foreach ($params as $k => $v) {
            foreach ($sensitiveKeys as $sk) {
                if (str_contains(strtolower((string) $k), $sk)) {
                    $params[ $k ] = '[REDACTED_SECRET]';
                }
            }
        }
        return $params;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}
