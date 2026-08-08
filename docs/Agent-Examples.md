# Multi-Agent Workflow Example

```php
use WPAIOS\Modules\Agents\BuiltIn\ResearchAgent;
use WPAIOS\Modules\Agents\Context\AgentContext;

$agent = new ResearchAgent();
$context = new AgentContext('task_001');

$result = $agent->executeTask($task, $context);
```
