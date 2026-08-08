<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Providers;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Agents\Approvals\ApprovalManager;
use WPAIOS\Modules\Agents\Audit\AgentAuditLogger;
use WPAIOS\Modules\Agents\BuiltIn\ContentAgent;
use WPAIOS\Modules\Agents\BuiltIn\DeploymentAgent;
use WPAIOS\Modules\Agents\BuiltIn\DesignAgent;
use WPAIOS\Modules\Agents\BuiltIn\ElementorAgent;
use WPAIOS\Modules\Agents\BuiltIn\FormsAgent;
use WPAIOS\Modules\Agents\BuiltIn\MediaAgent;
use WPAIOS\Modules\Agents\BuiltIn\OrchestratorAgent;
use WPAIOS\Modules\Agents\BuiltIn\QAAgent;
use WPAIOS\Modules\Agents\BuiltIn\ResearchAgent;
use WPAIOS\Modules\Agents\BuiltIn\SecurityAgent;
use WPAIOS\Modules\Agents\BuiltIn\SEOAgent;
use WPAIOS\Modules\Agents\BuiltIn\WebsiteArchitectAgent;
use WPAIOS\Modules\Agents\BuiltIn\WooCommerceAgent;
use WPAIOS\Modules\Agents\Handoffs\HandoffManager;
use WPAIOS\Modules\Agents\Orchestrator\AgentOrchestrator;
use WPAIOS\Modules\Agents\Planner\AgentPlanner;
use WPAIOS\Modules\Agents\Registry\AgentRegistry;
use WPAIOS\Modules\Agents\Safety\LoopProtector;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Class AgentsServiceProvider
 * Registers all 13 built-in agents and orchestrator services into DI container.
 */
class AgentsServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(AgentRegistry::class, function () {
            $registry = new AgentRegistry();
            $registry->register(new OrchestratorAgent());
            $registry->register(new ResearchAgent());
            $registry->register(new WebsiteArchitectAgent());
            $registry->register(new ContentAgent());
            $registry->register(new DesignAgent());
            $registry->register(new ElementorAgent());
            $registry->register(new WooCommerceAgent());
            $registry->register(new SEOAgent());
            $registry->register(new MediaAgent());
            $registry->register(new FormsAgent());
            $registry->register(new QAAgent());
            $registry->register(new SecurityAgent());
            $registry->register(new DeploymentAgent());
            return $registry;
        });

        $this->container->singleton(AgentPlanner::class, fn () => new AgentPlanner());
        $this->container->singleton(LoopProtector::class, fn () => new LoopProtector());
        $this->container->singleton(ApprovalManager::class, fn () => new ApprovalManager());
        $this->container->singleton(AgentAuditLogger::class, fn () => new AgentAuditLogger());

        $this->container->singleton(HandoffManager::class, function (Container $c) {
            return new HandoffManager($c->get(LoopProtector::class));
        });

        $this->container->singleton(AgentOrchestrator::class, function (Container $c) {
            return new AgentOrchestrator(
                $c->get(AgentRegistry::class),
                $c->get(AgentPlanner::class),
                $c->get(LoopProtector::class),
                $c->get(ApprovalManager::class),
                $c->get(HandoffManager::class),
                $c->get(AgentAuditLogger::class)
            );
        });

        $this->container->singleton(AgentsManager::class, function (Container $c) {
            return new AgentsManager(
                $c->get(AgentRegistry::class),
                $c->get(AgentOrchestrator::class),
                $c->get(AgentPlanner::class),
                $c->get(ApprovalManager::class),
                $c->get(LoopProtector::class),
                $c->get(HandoffManager::class),
                $c->get(AgentAuditLogger::class)
            );
        });
    }

    public function boot(): void
    {
    }
}
