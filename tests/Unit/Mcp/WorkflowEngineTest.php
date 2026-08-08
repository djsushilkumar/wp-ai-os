<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Mcp;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Mcp\Workflows\AbstractWorkflowStep;
use WPAIOS\Modules\Mcp\Workflows\WorkflowEngine;

class StepOne extends AbstractWorkflowStep
{
    public function name(): string
    {
        return 'step_one';
    }

    public function execute(array $context): array
    {
        return ['step_one_done' => true];
    }
}

class StepTwo extends AbstractWorkflowStep
{
    public function name(): string
    {
        return 'step_two';
    }

    public function execute(array $context): array
    {
        return ['step_two_done' => true];
    }
}

class WorkflowEngineTest extends TestCase
{
    public function testSequentialWorkflowExecution(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $engine = new WorkflowEngine($logger);

        $steps = [new StepOne(), new StepTwo()];
        $result = $engine->run('test_workflow', $steps, ['initial' => 'value']);

        $this->assertTrue($result['step_one_done']);
        $this->assertTrue($result['step_two_done']);
        $this->assertEquals('value', $result['initial']);
    }
}
