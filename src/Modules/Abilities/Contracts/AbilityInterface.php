<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Contracts;

/**
 * Ability Interface contract for WP AI OS Abilities.
 */
interface AbilityInterface
{
    public function id(): string;
    public function name(): string;
    public function description(): string;
    public function category(): string;
    public function version(): string;

    /**
     * Required capability permissions.
     *
     * @return string[]
     */
    public function permissions(): array;

    /**
     * JSON Schema for ability arguments.
     *
     * @return array<string, mixed>
     */
    public function schema(): array;

    /**
     * Execute the ability.
     *
     * @param array<string, mixed> $params
     * @return mixed
     */
    public function execute(array $params): mixed;

    public function validate(array $params): bool;
    public function authorize(): bool;
    public function metadata(): array;
}
