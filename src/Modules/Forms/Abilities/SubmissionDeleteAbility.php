<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Abilities;

use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: forms/submissions/delete
 */
class SubmissionDeleteAbility extends AbstractAbility
{
    public function __construct(private FormsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_forms_submissions_delete';
    }

    public function getDescription(): string
    {
        return 'Delete a form submission by ID (for GDPR data erasure compliance).';
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
        $ok = $adapter ? $adapter->deleteSubmission($subId) : false;

        return [
            'success' => $ok,
            'deleted_id' => $subId,
        ];
    }
}
