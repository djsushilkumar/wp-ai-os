<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Builders\Validators\BuilderValidator;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/pages/validate
 */
class BuilderPagesValidateAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_pages_validate';
    }

    public function getDescription(): string
    {
        return 'Validate normalized builder document schema and structure.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['document'],
            'properties' => [
                'document' => ['type' => 'object', 'description' => 'Normalized document data'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $docData = $params['document'];
        $doc = new BuilderDocument($docData['id'] ?? 'doc_1', $docData['title'] ?? 'Test', $docData['nodes'] ?? []);

        $validator = new BuilderValidator();
        $warnings = $validator->validate($doc);

        return [
            'success' => true,
            'is_valid' => empty($warnings),
            'warnings' => $warnings,
        ];
    }
}
