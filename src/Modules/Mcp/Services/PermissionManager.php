<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

use WPAIOS\Support\Permission;

/**
 * Permission Manager checking ability capability requirements and user scopes.
 */
class PermissionManager
{
    public function __construct(
        private Permission $permission,
        private CapabilityRegistry $capabilityRegistry
    ) {
    }

    /**
     * Authorize user access for a specific ability.
     *
     * @param string $abilityId
     * @return bool
     */
    public function authorizeAbility(string $abilityId): bool
    {
        $requiredCaps = $this->capabilityRegistry->getCapabilities($abilityId);
        foreach ($requiredCaps as $cap) {
            if (!$this->permission->check($cap)) {
                return false;
            }
        }
        return true;
    }
}
