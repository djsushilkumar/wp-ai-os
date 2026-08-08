<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Abilities;

use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Automation\Workflow\WorkflowEngine;

/**
 * Workflow Automation Ability — exposes all autonomous workflows to MCP agents.
 */
class WorkflowAbility extends AbstractAbility
{
    protected string $category = 'Automation';
    protected array $permissions = ['manage_options'];

    public function __construct(private WorkflowEngine $engine)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_execute_workflow';
    }

    public function name(): string
    {
        return 'Autonomous Workflow Engine';
    }

    public function description(): string
    {
        return 'Execute autonomous multi-step WordPress workflows with planning, validation, retries, checkpointing, and automatic rollback on failure.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'workflow_id' => [
                    'type' => 'string',
                    'description' => 'Target workflow ID (e.g. wp_ai_os_create_landing_page).',
                ],
                'input' => [
                    'type' => 'object',
                    'description' => 'Workflow input parameters.',
                ],
            ],
            'required' => ['workflow_id'],
        ];
    }

    public function execute(array $params): mixed
    {
        $workflowId = $params['workflow_id'] ?? '';
        $input = $params['input'] ?? [];

        $result = $this->engine->run($workflowId, $input);
        return $result->toArray();
    }
}
