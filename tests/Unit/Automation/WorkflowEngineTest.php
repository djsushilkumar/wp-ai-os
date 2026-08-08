<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Automation;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Automation\Executor\TaskExecutor;
use WPAIOS\Modules\Automation\Memory\CheckpointMemory;
use WPAIOS\Modules\Automation\Models\TaskResult;
use WPAIOS\Modules\Automation\Models\WorkflowContext;
use WPAIOS\Modules\Automation\Planner\DependencyPlanner;
use WPAIOS\Modules\Automation\Planner\RiskAnalyzer;
use WPAIOS\Modules\Automation\Planner\TaskPlanner;
use WPAIOS\Modules\Automation\Queue\WorkflowQueue;
use WPAIOS\Modules\Automation\Rollback\RollbackManager;
use WPAIOS\Modules\Automation\Workflow\AbstractTask;
use WPAIOS\Modules\Automation\Workflow\WorkflowEngine;
use WPAIOS\Modules\Automation\Workflows\CreateLandingPageWorkflow;

class WorkflowEngineTest extends TestCase
{
    private WorkflowEngine $engine;

    protected function setUp(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $planner = new TaskPlanner(new DependencyPlanner(), new RiskAnalyzer());
        $executor = new TaskExecutor($logger);
        $rollback = new RollbackManager($logger);
        $checkpoint = new CheckpointMemory();
        $queue = new WorkflowQueue();

        $this->engine = new WorkflowEngine($planner, $executor, $rollback, $checkpoint, $queue, $logger);
    }

    public function testSuccessfulWorkflowExecution(): void
    {
        $workflow = new CreateLandingPageWorkflow();
        $this->engine->registerWorkflow($workflow);

        $result = $this->engine->run($workflow, ['title' => 'Unit Test Page']);

        $this->assertTrue($result->success);
        $this->assertEquals('completed', $result->status);
        $this->assertCount(2, $result->taskResults);
    }

    public function testDependencyPlannerOrdering(): void
    {
        $planner = new DependencyPlanner();

        $taskB = new class () extends AbstractTask {
            public function id(): string
            {
                return 'task_b';
            }
            public function name(): string
            {
                return 'Task B';
            }
            public function description(): string
            {
                return '';
            }
            public function dependencies(): array
            {
                return ['task_a'];
            }
            public function run(WorkflowContext $context): TaskResult
            {
                return $this->success(true);
            }
        };

        $taskA = new class () extends AbstractTask {
            public function id(): string
            {
                return 'task_a';
            }
            public function name(): string
            {
                return 'Task A';
            }
            public function description(): string
            {
                return '';
            }
            public function run(WorkflowContext $context): TaskResult
            {
                return $this->success(true);
            }
        };

        $resolved = $planner->resolve([$taskB, $taskA]);

        $this->assertEquals('task_a', $resolved[0]->id());
        $this->assertEquals('task_b', $resolved[1]->id());
    }
}
