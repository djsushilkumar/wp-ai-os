<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Registry;

use Exception;
use WPAIOS\Modules\Abilities\Contracts\AbilityInterface;

/**
 * Ability Registry storing and managing active WP AI OS Abilities.
 */
class AbilityRegistry
{
    /**
     * @var array<string, AbilityInterface>
     */
    private array $abilities = [];

    public function register(AbilityInterface $ability): void
    {
        $this->abilities[ $ability->id() ] = $ability;
    }

    public function get(string $id): AbilityInterface
    {
        if (! isset($this->abilities[ $id ])) {
            throw new Exception(sprintf('Ability [%s] is not registered.', $id));
        }

        return $this->abilities[ $id ];
    }

    public function has(string $id): bool
    {
        return isset($this->abilities[ $id ]);
    }

    /**
     * Get abilities by category.
     *
     * @param string $category
     * @return AbilityInterface[]
     */
    public function getByCategory(string $category): array
    {
        $filtered = [];
        foreach ($this->abilities as $ability) {
            if (strtolower($ability->category()) === strtolower($category)) {
                $filtered[] = $ability;
            }
        }
        return $filtered;
    }

    /**
     * @return array<string, AbilityInterface>
     */
    public function all(): array
    {
        return $this->abilities;
    }
}
