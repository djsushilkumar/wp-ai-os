<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Agents;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use WPAIOS\Modules\Agents\Approvals\ApprovalManager;
use WPAIOS\Modules\Agents\Audit\AgentAuditLogger;
use WPAIOS\Modules\Agents\BuiltIn\DeploymentAgent;
use WPAIOS\Modules\Agents\BuiltIn\OrchestratorAgent;
use WPAIOS\Modules\Agents\BuiltIn\ResearchAgent;
use WPAIOS\Modules\Agents\Context\AgentContext;
use WPAIOS\Modules\Agents\Handoffs\HandoffManager;
use WPAIOS\Modules\Agents\Orchestrator\AgentOrchestrator;
use WPAIOS\Modules\Agents\Planner\AgentPlanner;
use WPAIOS\Modules\Agents\Registry\AgentRegistry;
use WPAIOS\Modules\Agents\Safety\LoopProtector;

class AgentsFrameworkTest extends TestCase
{
    public function testAgentRegistry13BuiltInAgents(): void
    {
        $registry = new AgentRegistry();
        $registry->register(new OrchestratorAgent());
        $registry->register(new ResearchAgent());
        $registry->register(new DeploymentAgent());

        $this->assertTrue($registry->has('orchestrator'));
        $this->assertTrue($registry->has('research'));
        $this->assertTrue($registry->has('deployment'));

        $this->assertEquals('LOW', $registry->get('orchestrator')->getRiskLevel());
        $this->assertEquals('CRITICAL', $registry->get('deployment')->getRiskLevel());
    }

    public function testHumanApprovalForCriticalRiskTask(): void
    {
        $registry = new AgentRegistry();
        $deploymentAgent = new DeploymentAgent();
        $registry->register($deploymentAgent);

        $planner = new AgentPlanner();
        $loopProtector = new LoopProtector();
        $approvalManager = new ApprovalManager();
        $auditLogger = new AgentAuditLogger();
        $handoffManager = new HandoffManager($loopProtector);

        $orchestrator = new AgentOrchestrator(
            $registry,
            $planner,
            $loopProtector,
            $approvalManager,
            $handoffManager,
            $auditLogger
        );

        $task = new class ('task_crit', 'Deploy site to production') implements \WPAIOS\Modules\Agents\Contracts\AgentTaskInterface {
            public function __construct(private string $id, private string $goal)
            {
            }
            public function getId(): string
            {
                return $this->id;
            }
            public function getGoal(): string
            {
                return $this->goal;
            }
            public function getStatus(): string
            {
                return 'pending';
            }
            public function getInputs(): array
            {
                return [];
            }
        };

        $context = new AgentContext('task_crit');
        $result = $orchestrator->runTask($deploymentAgent, $task, $context);

        $this->assertEquals('paused_pending_approval', $result['status']);
        $this->assertCount(1, $approvalManager->getPendingApprovals());

        $appId = $result['approval_id'];
        $approved = $approvalManager->approve($appId, 'admin_user', 'Authorized');
        $this->assertTrue($approved);
        $this->assertEmpty($approvalManager->getPendingApprovals());
    }

    public function testLoopProtectorThresholds(): void
    {
        $protector = new LoopProtector();

        $this->expectException(RuntimeException::class);
        for ($i = 0; $i < 30; $i++) {
            $protector->recordStep();
        }
    }

    public function testAuditLoggerSecretIsolation(): void
    {
        $logger = new AgentAuditLogger();
        $entry = $logger->log(
            'research',
            'task_001',
            'wp_ai_os_get_system_info',
            ['api_key' => 'secret_12345', 'user' => 'john'],
            'completed',
            0.05
        );

        $this->assertEquals('completed', $entry['status']);
        $this->assertCount(1, $logger->getLogs());
    }
}
