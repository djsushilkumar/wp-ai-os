<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/get
 */
class FormGetAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_get';
    }

    public function getDescription(): string
    {
        return 'Get detailed schema and fields of a specific form by ID.';
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
        $form = $this->manager->getRepository()->findById($id, $provider);

        if (!$form) {
            return ['success' => false, 'error' => 'Form not found'];
        }

        return [
            'success' => true,
            'form' => $form->toArray(),
        ];
    }
}
