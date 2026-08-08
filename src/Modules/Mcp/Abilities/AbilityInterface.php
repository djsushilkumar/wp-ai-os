<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Abilities;

/**
 * Generic Ability API Interface contract for WP AI OS Agent Abilities.
 */
interface AbilityInterface
{
    /**
     * Unique identifier for the ability (e.g., 'wp_ai_os_inspect_site').
     *
     * @return string
     */
    public function id(): string;

    /**
     * Human readable name of the ability.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Detailed description of what the ability performs.
     *
     * @return string
     */
    public function description(): string;

    /**
     * Ability version string.
     *
     * @return string
     */
    public function version(): string;

    /**
     * List of required WordPress capabilities (e.g., ['manage_options']).
     *
     * @return string[]
     */
    public function permissions(): array;

    /**
     * Input arguments JSON Schema specification array.
     *
     * @return array<string, mixed>
     */
    public function schema(): array;

    /**
     * Execute the ability with sanitized parameters.
     *
     * @param array<string, mixed> $params
     * @return mixed
     */
    public function execute(array $params): mixed;

    /**
     * Validate parameter arguments against JSON schema.
     *
     * @param array<string, mixed> $params
     * @return bool
     */
    public function validate(array $params): bool;

    /**
     * Authorize user access against permissions.
     *
     * @return bool
     */
    public function authorize(): bool;

    /**
     * Get ability category/domain metadata.
     *
     * @return array<string, mixed>
     */
    public function metadata(): array;

    /**
     * Check runtime health status of ability.
     *
     * @return array{healthy: bool, message: string}
     */
    public function health(): array;
}
