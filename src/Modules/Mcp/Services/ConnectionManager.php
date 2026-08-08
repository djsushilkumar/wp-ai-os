<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Connection Manager tracking active MCP client sessions and transport states.
 */
class ConnectionManager
{
    /**
     * @var array<string, array{client_id: string, transport: string, connected_at: int, last_active: int}>
     */
    private array $activeConnections = [];

    /**
     * Track a new connection.
     *
     * @param string $clientId
     * @param string $transport 'sse', 'http', or 'cli'
     * @return void
     */
    public function connect(string $clientId, string $transport = 'http'): void
    {
        $this->activeConnections[$clientId] = [
            'client_id' => $clientId,
            'transport' => $transport,
            'connected_at' => time(),
            'last_active' => time(),
        ];
    }

    /**
     * Refresh connection activity.
     *
     * @param string $clientId
     * @return void
     */
    public function touch(string $clientId): void
    {
        if (isset($this->activeConnections[$clientId])) {
            $this->activeConnections[$clientId]['last_active'] = time();
        }
    }

    /**
     * Disconnect client.
     *
     * @param string $clientId
     * @return void
     */
    public function disconnect(string $clientId): void
    {
        unset($this->activeConnections[$clientId]);
    }

    /**
     * Get active connections list.
     *
     * @return array<string, array{client_id: string, transport: string, connected_at: int, last_active: int}>
     */
    public function getActiveConnections(): array
    {
        return $this->activeConnections;
    }
}
