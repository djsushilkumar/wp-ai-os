<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

use WPAIOS\Contracts\LoggerInterface;

/**
 * Immutable Audit Logger service recording MCP tool calls, parameters, latency, and status.
 */
class AuditLogger
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Log an MCP execution record.
     *
     * @param string $abilityName
     * @param array<string, mixed> $params
     * @param string $status 'success' or 'error'
     * @param float $latencyMs
     * @param int $userId
     * @return void
     */
    public function logExecution(string $abilityName, array $params, string $status, float $latencyMs, int $userId = 0): void
    {
        global $wpdb;

        $sanitizedParams = json_encode($params);

        if (isset($wpdb)) {
            $tableName = $wpdb->prefix . 'ai_os_audit_log';
            $wpdb->insert(
                $tableName,
                [
                    'timestamp' => current_time('mysql'),
                    'user_id' => $userId,
                    'ability_name' => $abilityName,
                    'parameters' => $sanitizedParams,
                    'status' => $status,
                    'latency_ms' => $latencyMs,
                ],
                ['%s', '%d', '%s', '%s', '%s', '%f']
            );
        }

        $this->logger->info(sprintf('[MCP Audit Log] Ability [%s] executed with status [%s] in %.2fms.', $abilityName, $status, $latencyMs));
    }
}
