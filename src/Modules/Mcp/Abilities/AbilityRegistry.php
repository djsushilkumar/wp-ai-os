<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Abilities;

use Exception;

/**
 * Ability Registry storing and resolving registered Agent Abilities.
 */
class AbilityRegistry
{
    /**
     * @var array<string, AbilityInterface>
     */
    private array $abilities = [];

    /**
     * Register an ability.
     *
     * @param AbilityInterface $ability
     * @return void
     */
    public function register(AbilityInterface $ability): void
    {
        $this->abilities[$ability->id()] = $ability;
    }

    /**
     * Get ability by ID.
     *
     * @param string $id
     * @return AbilityInterface
     * @throws Exception
     */
    public function get(string $id): AbilityInterface
    {
        if (!isset($this->abilities[$id])) {
            throw new Exception(sprintf('Ability [%s] is not registered.', $id));
        }

        return $this->abilities[$id];
    }

    /**
     * Check if ability is registered.
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->abilities[$id]);
    }

    /**
     * Get all registered abilities.
     *
     * @return array<string, AbilityInterface>
     */
    public function all(): array
    {
        return $this->abilities;
    }
}
