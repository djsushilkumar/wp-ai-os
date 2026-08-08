<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Capability Registry managing granular permission scopes for agent abilities.
 */
class CapabilityRegistry
{
    /**
     * @var array<string, string[]>
     */
    private array $capabilities = [];

    /**
     * Register required capability scopes for an ability.
     *
     * @param string $abilityId
     * @param string[] $requiredCaps
     * @return void
     */
    public function registerCapabilities(string $abilityId, array $requiredCaps): void
    {
        $this->capabilities[$abilityId] = $requiredCaps;
    }

    /**
     * Get required capabilities for an ability.
     *
     * @param string $abilityId
     * @return string[]
     */
    public function getCapabilities(string $abilityId): array
    {
        return $this->capabilities[$abilityId] ?? ['manage_options'];
    }
}
