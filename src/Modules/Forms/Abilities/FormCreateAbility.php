<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Forms\Services\FormFactory;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/create
 */
class FormCreateAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_create';
    }

    public function getDescription(): string
    {
        return 'Create a new form with structured fields.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['title'],
            'properties' => [
                'title' => ['type' => 'string', 'description' => 'Form title'],
                'description' => ['type' => 'string', 'description' => 'Form description'],
                'provider' => ['type' => 'string', 'description' => 'Target form provider slug'],
                'fields' => ['type' => 'array', 'description' => 'List of field definitions'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $providerSlug = $params['provider'] ?? 'wp_ai_os_native';
        $form = FormFactory::createForm($params, $providerSlug);
        $saved = $this->manager->getRepository()->save($form, $providerSlug);

        return [
            'success' => true,
            'form' => $saved->toArray(),
        ];
    }
}
