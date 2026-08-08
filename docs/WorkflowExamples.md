# Workflow Execution Code Examples — WP AI OS

## Executing a Workflow via WorkflowEngine

```php
use WPAIOS\Modules\Automation\Workflow\WorkflowEngine;

// 1. Resolve WorkflowEngine from DI Container
$engine = $container->get(WorkflowEngine::class);

// 2. Execute workflow synchronously with full planning & safety rollbacks
$result = $engine->run('wp_ai_os_create_landing_page', [
    'title' => 'Black Friday Special Landing Page'
]);

if ($result->success) {
    echo "Workflow completed successfully in {$result->totalDurationMs}ms!\n";
} else {
    echo "Workflow failed: {$result->error}\n";
    print_r($result->rollbackLog);
}
```

---

## Executing a Workflow via MCP Ability (Antigravity Agent IDE)

```json
{
    "tool": "wp_ai_os_execute_workflow",
    "arguments": {
        "workflow_id": "wp_ai_os_create_landing_page",
        "input": {
            "title": "Enterprise AI Solutions"
        }
    }
}
```
