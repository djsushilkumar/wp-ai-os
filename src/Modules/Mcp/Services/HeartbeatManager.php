<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Heartbeat Manager monitoring client connection health and pruning stale sessions.
 */
class HeartbeatManager
{
    public function __construct(private ConnectionManager $connectionManager)
    {
    }

    /**
     * Perform heartbeat check and prune idle sessions.
     *
     * @param int $maxIdleSeconds Default 300 seconds (5 mins).
     * @return int Count of pruned sessions.
     */
    public function pulse(int $maxIdleSeconds = 300): int
    {
        $pruned = 0;
        $now = time();

        foreach ($this->connectionManager->getActiveConnections() as $clientId => $conn) {
            if ($now - $conn['last_active'] > $maxIdleSeconds) {
                $this->connectionManager->disconnect($clientId);
                $pruned++;
            }
        }

        return $pruned;
    }
}
