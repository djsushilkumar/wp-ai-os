<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Session Manager maintaining client session context across MCP tool invocations.
 */
class SessionManager
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $sessions = [];

    /**
     * Set session variable.
     *
     * @param string $sessionId
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $sessionId, string $key, mixed $value): void
    {
        $this->sessions[$sessionId][$key] = $value;
    }

    /**
     * Get session variable.
     *
     * @param string $sessionId
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $sessionId, string $key, mixed $default = null): mixed
    {
        return $this->sessions[$sessionId][$key] ?? $default;
    }

    /**
     * Clear session context.
     *
     * @param string $sessionId
     * @return void
     */
    public function clear(string $sessionId): void
    {
        unset($this->sessions[$sessionId]);
    }
}
