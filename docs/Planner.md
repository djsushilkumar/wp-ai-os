# Task Planner & Risk Analyzer Specification — WP AI OS

## Dependency Planner

The `DependencyPlanner` evaluates task dependencies declared via `$task->dependencies()` and resolves a topological execution order.

```php
use WPAIOS\Modules\Automation\Planner\DependencyPlanner;

$planner = new DependencyPlanner();
$orderedTasks = $planner->resolve([$taskB, $taskA]); // Task A runs before Task B
```

---

## Risk Analyzer

The `RiskAnalyzer` evaluates potential destructive tasks (such as deletions, drops, database updates, or plugin toggles) and assigns a safety score and risk tier.

```php
use WPAIOS\Modules\Automation\Planner\RiskAnalyzer;

$analyzer = new RiskAnalyzer();
$analysis = $analyzer->analyze($tasks);

// Returns: ['level' => 'high', 'score' => 35, 'high_risk_tasks' => [...], 'warnings' => [...]]
```
