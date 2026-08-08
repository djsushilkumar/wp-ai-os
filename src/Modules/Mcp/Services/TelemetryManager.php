<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Telemetry Manager tracking execution latency, tool invocation counters, and system health metrics.
 */
class TelemetryManager
{
    /**
     * @var array<string, int>
     */
    private array $counters = [];

    /**
     * Increment metric counter.
     *
     * @param string $metric
     * @param int $amount
     * @return void
     */
    public function increment(string $metric, int $amount = 1): void
    {
        if (!isset($this->counters[$metric])) {
            $this->counters[$metric] = 0;
        }
        $this->counters[$metric] += $amount;
    }

    /**
     * Get all collected metrics.
     *
     * @return array<string, int>
     */
    public function getMetrics(): array
    {
        return $this->counters;
    }
}
