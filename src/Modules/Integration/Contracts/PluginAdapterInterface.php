<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Contracts;

/**
 * Universal Plugin Adapter Interface — contract for third-party WordPress plugin integrations.
 */
interface PluginAdapterInterface
{
    public function id(): string;
    public function name(): string;
    public function minVersion(): string;

    public function detect(): bool;
    public function boot(): void;

    /**
     * Diagnostic health status check for the target plugin.
     *
     * @return array{status: string, message: string, version: string}
     */
    public function health(): array;

    /**
     * Registered WP AI OS Abilities provided by this adapter.
     *
     * @return array<mixed>
     */
    public function abilities(): array;

    /**
     * Required capability permissions for this integration.
     *
     * @return string[]
     */
    public function permissions(): array;

    /**
     * Registered MCP Tools exposed by this adapter.
     *
     * @return array<mixed>
     */
    public function tools(): array;

    /**
     * Registered MCP Resources exposed by this adapter.
     *
     * @return array<mixed>
     */
    public function resources(): array;

    public function shutdown(): void;
}
