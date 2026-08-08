<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/duplicate
 */
class FormDuplicateAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_duplicate';
    }

    public function getDescription(): string
    {
        return 'Duplicate an existing form.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['form_id'],
            'properties' => [
                'form_id' => ['type' => 'string', 'description' => 'Form ID'],
                'provider' => ['type' => 'string', 'description' => 'Optional provider slug'],
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
        $copy = $adapter ? $adapter->duplicateForm($id) : null;

        return [
            'success' => null !== $copy,
            'duplicated_form' => $copy ? $copy->toArray() : null,
        ];
    }
}
