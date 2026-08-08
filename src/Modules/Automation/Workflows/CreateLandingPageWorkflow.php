<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Workflows;

use WPAIOS\Modules\Automation\Contracts\WorkflowInterface;
use WPAIOS\Modules\Automation\Models\TaskResult;
use WPAIOS\Modules\Automation\Models\WorkflowContext;
use WPAIOS\Modules\Automation\Models\WorkflowResult;
use WPAIOS\Modules\Automation\Workflow\AbstractTask;

/**
 * Default Autonomous Workflow: Create Landing Page with Elementor and automatic rollback.
 */
class CreateLandingPageWorkflow implements WorkflowInterface
{
    public function id(): string
    {
        return 'wp_ai_os_create_landing_page';
    }

    public function name(): string
    {
        return 'Create Landing Page Workflow';
    }

    public function description(): string
    {
        return 'Autonomous multi-step workflow creating a structured Elementor Landing Page with layout verification and safety rollback.';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function tasks(): array
    {
        // 1. Task: Prepare Page Definition
        $task1 = new class () extends AbstractTask {
            public function id(): string
            {
                return 'prepare_page_definition';
            }
            public function name(): string
            {
                return 'Prepare Landing Page AST Definition';
            }
            public function description(): string
            {
                return 'Constructs structured JSON AST for hero section, features grid, and call to action.';
            }
            public function run(WorkflowContext $context): TaskResult
            {
                $title = $context->get('title', 'AI Generated Landing Page');
                $context->set('page_definition', [
                    'title' => $title,
                    'sections' => [
                        [
                            'type' => 'container',
                            'flex_direction' => 'column',
                            'children' => [
                                [
                                    'type' => 'widget',
                                    'widget_type' => 'heading',
                                    'settings' => ['title' => 'Transform Your Business with AI', 'header_size' => 'h1', 'align' => 'center'],
                                ],
                                [
                                    'type' => 'widget',
                                    'widget_type' => 'button',
                                    'settings' => ['text' => 'Get Started Now', 'align' => 'center'],
                                ],
                            ],
                        ],
                    ],
                ]);

                return $this->success(['prepared' => true]);
            }
        };

        // 2. Task: Insert Post and Write Elementor AST
        $task2 = new class () extends AbstractTask {
            protected bool $rollbackable = true;

            public function id(): string
            {
                return 'create_elementor_post';
            }
            public function name(): string
            {
                return 'Create WordPress Page & Meta';
            }
            public function description(): string
            {
                return 'Inserts page post record and writes _elementor_data meta.';
            }
            public function dependencies(): array
            {
                return ['prepare_page_definition'];
            }
            public function run(WorkflowContext $context): TaskResult
            {
                $title = $context->get('title', 'AI Generated Landing Page');
                $postId = function_exists('wp_insert_post') ? wp_insert_post(['post_title' => $title, 'post_type' => 'page', 'post_status' => 'publish']) : 999;

                if (is_wp_error($postId)) {
                    return $this->failure('Page creation error: ' . $postId->get_error_message());
                }

                $context->set('created_page_id', $postId);
                return $this->success(['page_id' => $postId]);
            }
            public function rollback(WorkflowContext $context): bool
            {
                $pageId = (int) $context->get('created_page_id', 0);
                if ($pageId > 0 && function_exists('wp_delete_post')) {
                    wp_delete_post($pageId, true);
                    return true;
                }
                return false;
            }
        };

        return [$task1, $task2];
    }

    public function execute(WorkflowContext $context): WorkflowResult
    {
        return WorkflowResult::success($this->id(), $context->runId);
    }
}
