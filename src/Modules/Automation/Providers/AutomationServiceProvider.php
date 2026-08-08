<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Providers;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Automation\Abilities\WorkflowAbility;
use WPAIOS\Modules\Automation\Executor\TaskExecutor;
use WPAIOS\Modules\Automation\Memory\CheckpointMemory;
use WPAIOS\Modules\Automation\Planner\DependencyPlanner;
use WPAIOS\Modules\Automation\Planner\RiskAnalyzer;
use WPAIOS\Modules\Automation\Planner\TaskPlanner;
use WPAIOS\Modules\Automation\Queue\WorkflowQueue;
use WPAIOS\Modules\Automation\Rollback\RollbackManager;
use WPAIOS\Modules\Automation\Workflow\WorkflowEngine;
use WPAIOS\Modules\Automation\Workflows\CreateLandingPageWorkflow;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider binding all Autonomous Workflow Engine components into Container.
 */
class AutomationServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // 1. Planner Subsystems
        $this->container->singleton(DependencyPlanner::class);
        $this->container->singleton(RiskAnalyzer::class);
        $this->container->singleton(TaskPlanner::class, function () {
            return new TaskPlanner(
                $this->container->get(DependencyPlanner::class),
                $this->container->get(RiskAnalyzer::class)
            );
        });

        // 2. Executor & Rollback Subsystems
        $this->container->singleton(TaskExecutor::class, function () {
            return new TaskExecutor($this->container->get(LoggerInterface::class));
        });
        $this->container->singleton(RollbackManager::class, function () {
            return new RollbackManager($this->container->get(LoggerInterface::class));
        });

        // 3. Queue & Checkpoint Memory
        $this->container->singleton(WorkflowQueue::class);
        $this->container->singleton(CheckpointMemory::class);

        // 4. Core Workflow Engine
        $this->container->singleton(WorkflowEngine::class, function () {
            return new WorkflowEngine(
                planner: $this->container->get(TaskPlanner::class),
                executor: $this->container->get(TaskExecutor::class),
                rollbackManager: $this->container->get(RollbackManager::class),
                checkpointMemory: $this->container->get(CheckpointMemory::class),
                queue: $this->container->get(WorkflowQueue::class),
                logger: $this->container->get(LoggerInterface::class),
                eventDispatcher: $this->container->get(EventDispatcherInterface::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var WorkflowEngine $engine */
        $engine = $this->container->get(WorkflowEngine::class);

        // Register default workflows
        $engine->registerWorkflow(new CreateLandingPageWorkflow());

        // Register WorkflowAbility in AbilityRegistry if present
        if ($this->container->has(AbilityRegistry::class)) {
            /** @var AbilityRegistry $abilityRegistry */
            $abilityRegistry = $this->container->get(AbilityRegistry::class);
            $abilityRegistry->register(new WorkflowAbility($engine));
        }
    }
}
