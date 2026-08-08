<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/providers/list
 */
class ProvidersListAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_providers_list';
    }

    public function getDescription(): string
    {
        return 'List all discovered WordPress form provider plugins and their activation status.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): array
    {
        $report = $this->manager->getDiscovery()->discoverProviders();
        return [
            'success' => true,
            'providers' => $report,
        ];
    }
}
