<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/delete
 */
class FormDeleteAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_delete';
    }

    public function getDescription(): string
    {
        return 'Delete a form by ID.';
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
        $success = $this->manager->getRepository()->delete($id, $provider);

        return [
            'success' => $success,
            'deleted_id' => $id,
        ];
    }
}
