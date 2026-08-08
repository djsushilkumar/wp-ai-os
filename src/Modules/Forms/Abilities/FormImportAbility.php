<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Forms\Services\FormFactory;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/import
 */
class FormImportAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_import';
    }

    public function getDescription(): string
    {
        return 'Import form definition from JSON payload.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['data'],
            'properties' => [
                'data' => ['type' => 'object', 'description' => 'Exported form data structure'],
                'provider' => ['type' => 'string', 'description' => 'Target form provider slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $data = $params['data'];
        $providerSlug = $params['provider'] ?? ($data['provider_slug'] ?? 'wp_ai_os_native');

        $form = FormFactory::createForm($data, $providerSlug);
        $imported = $this->manager->getRepository()->save($form, $providerSlug);

        return [
            'success' => true,
            'imported_form' => $imported->toArray(),
        ];
    }
}
