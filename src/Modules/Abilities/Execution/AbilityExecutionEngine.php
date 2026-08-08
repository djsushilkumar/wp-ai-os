<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Execution;

use Exception;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Contracts\AbilityInterface;

/**
 * Execution Engine running abilities with authorization, validation, latency metrics, and logging.
 */
class AbilityExecutionEngine
{
    public function __construct(
        private LoggerInterface $logger,
        private ?EventDispatcherInterface $eventDispatcher = null
    ) {
    }

    /**
     * Safely execute an ability.
     *
     * @param AbilityInterface     $ability
     * @param array<string, mixed> $params
     * @return mixed
     * @throws Exception
     */
    public function execute(AbilityInterface $ability, array $params = []): mixed
    {
        $startTime = microtime(true);
        $abilityId = $ability->id();

        // 1. Authorize
        if (! $ability->authorize()) {
            $this->logger->error(sprintf('[Ability Engine] Authorization failed for [%s].', $abilityId));
            throw new Exception(sprintf('Authorization Denied for ability [%s].', $abilityId));
        }

        // 2. Validate
        if (! $ability->validate($params)) {
            $this->logger->error(sprintf('[Ability Engine] Parameter validation failed for [%s].', $abilityId));
            throw new Exception(sprintf('Invalid parameters for ability [%s].', $abilityId));
        }

        // 3. Execute
        try {
            $result    = $ability->execute($params);
            $latencyMs = (microtime(true) - $startTime) * 1000;

            $this->logger->info(sprintf('[Ability Engine] Executed [%s] successfully in %.2fms.', $abilityId, $latencyMs));
            $this->eventDispatcher?->dispatch('ability.executed', $abilityId, $params, $result, $latencyMs);

            return $result;
        } catch (Exception $e) {
            $latencyMs = (microtime(true) - $startTime) * 1000;
            $this->logger->error(sprintf('[Ability Engine] Execution failed for [%s]: %s', $abilityId, $e->getMessage()));
            $this->eventDispatcher?->dispatch('ability.failed', $abilityId, $params, $e->getMessage(), $latencyMs);

            throw $e;
        }
    }
}
