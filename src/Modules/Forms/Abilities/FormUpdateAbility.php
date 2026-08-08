<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/update
 */
class FormUpdateAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_update';
    }

    public function getDescription(): string
    {
        return 'Update existing form metadata or fields.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['form_id'],
            'properties' => [
                'form_id' => ['type' => 'string', 'description' => 'Form ID'],
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'enabled' => ['type' => 'boolean'],
                'provider' => ['type' => 'string'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $id = $params['form_id'];
        $provider = $params['provider'] ?? null;
        $existing = $this->manager->getRepository()->findById($id, $provider);

        if (!$existing) {
            return ['success' => false, 'error' => 'Form not found'];
        }

        $adapter = $this->manager->getDiscovery()->getAdapter($existing->getProviderSlug());
        if (!$adapter) {
            return ['success' => false, 'error' => 'Form adapter unavailable'];
        }

        $ok = $adapter->updateForm($id, $params);
        return [
            'success' => $ok,
            'form_id' => $id,
        ];
    }
}
