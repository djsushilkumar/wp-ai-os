<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Automation\Planner;

use WPAIOS\Modules\Automation\Contracts\TaskInterface;

/**
 * Dependency Planner — resolves task execution order using Kahn's topological sort.
 */
class DependencyPlanner
{
    /**
     * Resolve a dependency-safe execution order from a task graph.
     *
     * @param TaskInterface[] $tasks
     * @return TaskInterface[] Ordered by dependency resolution.
     * @throws \RuntimeException If a circular dependency is detected.
     */
    public function resolve(array $tasks): array
    {
        $taskMap = [];
        foreach ($tasks as $task) {
            $taskMap[$task->id()] = $task;
        }

        // Build in-degree map and adjacency list
        $inDegree = [];
        $adjacency = [];

        foreach ($tasks as $task) {
            $id = $task->id();
            $inDegree[$id] = $inDegree[$id] ?? 0;

            foreach ($task->dependencies() as $depId) {
                $adjacency[$depId][] = $id;
                $inDegree[$id] = ($inDegree[$id] ?? 0) + 1;
            }
        }

        // Kahn's algorithm
        $queue = [];
        foreach ($inDegree as $id => $degree) {
            if ($degree === 0) {
                $queue[] = $id;
            }
        }

        $sorted = [];
        while (!empty($queue)) {
            $current = array_shift($queue);
            if (isset($taskMap[$current])) {
                $sorted[] = $taskMap[$current];
            }

            foreach ($adjacency[$current] ?? [] as $neighbor) {
                $inDegree[$neighbor]--;
                if ($inDegree[$neighbor] === 0) {
                    $queue[] = $neighbor;
                }
            }
        }

        if (count($sorted) !== count($tasks)) {
            throw new \RuntimeException('Circular dependency detected in workflow task graph.');
        }

        return $sorted;
    }
}
