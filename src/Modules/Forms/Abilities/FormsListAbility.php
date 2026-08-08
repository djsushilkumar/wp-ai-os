<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/list
 */
class FormsListAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_list';
    }

    public function getDescription(): string
    {
        return 'List all available forms across installed WordPress form providers.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'provider' => ['type' => 'string', 'description' => 'Optional provider slug filter (e.g. fluentform, gravityforms, wpforms, cf7, ninja_forms, formidable)'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $provider = $params['provider'] ?? null;
        $forms = $this->manager->getRepository()->findAll($provider);
        return [
            'success' => true,
            'count' => count($forms),
            'forms' => array_map(fn ($f) => $f->toArray(), $forms),
        ];
    }
}
