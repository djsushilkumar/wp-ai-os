<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/submissions/list
 */
class SubmissionsListAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_submissions_list';
    }

    public function getDescription(): string
    {
        return 'List submissions for a specific form with pagination and filtering.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['form_id'],
            'properties' => [
                'form_id' => ['type' => 'string', 'description' => 'Form ID'],
                'limit' => ['type' => 'integer', 'default' => 20],
                'offset' => ['type' => 'integer', 'default' => 0],
                'provider' => ['type' => 'string', 'description' => 'Optional provider slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $formId = $params['form_id'];
        $limit = $params['limit'] ?? 20;
        $offset = $params['offset'] ?? 0;
        $provider = $params['provider'] ?? null;

        $subs = $this->manager->getRepository()->findSubmissions($formId, $limit, $offset, [], $provider);

        return [
            'success' => true,
            'count' => count($subs),
            'submissions' => array_map(fn ($s) => $s->toArray(), $subs),
        ];
    }
}
