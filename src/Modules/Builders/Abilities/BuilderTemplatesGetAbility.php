<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builder/templates/get
 */
class BuilderTemplatesGetAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builder_templates_get';
    }

    public function getDescription(): string
    {
        return 'Get template details by ID.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'required' => ['template_id', 'builder'],
            'properties' => [
                'template_id' => ['type' => 'string', 'description' => 'Template ID'],
                'builder' => ['type' => 'string', 'description' => 'Builder slug'],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $templateId = $params['template_id'];
        $slug = $params['builder'];

        $adapter = $this->manager->getRegistry()->get($slug);
        $tpl = $adapter ? $adapter->exportTemplate($templateId) : null;

        if (empty($tpl)) {
            return ['success' => false, 'error' => 'Template not found'];
        }

        return [
            'success' => true,
            'template' => $tpl,
        ];
    }
}
