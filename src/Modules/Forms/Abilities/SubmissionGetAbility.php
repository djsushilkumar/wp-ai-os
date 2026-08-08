<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/submissions/get
 */
class SubmissionGetAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_submissions_get';
    }

    public function getDescription(): string
    {
        return 'Get single submission details by ID.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['submission_id'],
            'properties' => [
                'submission_id' => ['type' => 'string', 'description' => 'Submission ID'],
                'provider' => ['type' => 'string', 'description' => 'Optional provider slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $subId = $params['submission_id'];
        $provider = $params['provider'] ?? 'wp_ai_os_native';

        $adapter = $this->manager->getDiscovery()->getAdapter($provider);
        $sub = $adapter ? $adapter->getSubmission($subId) : null;

        if (!$sub) {
            return ['success' => false, 'error' => 'Submission not found'];
        }

        return [
            'success' => true,
            'submission' => $sub->toArray(),
        ];
    }
}
