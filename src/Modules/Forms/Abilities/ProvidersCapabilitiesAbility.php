<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/providers/capabilities
 */
class ProvidersCapabilitiesAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_providers_capabilities';
    }

    public function getDescription(): string
    {
        return 'Get feature matrix and capabilities of a specific form provider.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['provider'],
            'properties' => [
                'provider' => ['type' => 'string', 'description' => 'Provider slug (e.g. fluentform, gravityforms, wpforms, cf7, ninja_forms, formidable)'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $slug = $params['provider'];
        $adapter = $this->manager->getDiscovery()->getAdapter($slug);

        if (!$adapter) {
            return ['success' => false, 'error' => 'Provider not found'];
        }

        return [
            'success' => true,
            'provider' => $slug,
            'is_available' => $adapter->isAvailable(),
            'version' => $adapter->getVersion(),
            'capabilities' => $adapter->getCapabilities()->toArray(),
        ];
    }
}
