# Persistent Priority Queue Specification — WP AI OS

## Overview

The `WorkflowQueue` provides background asynchronous workflow execution with priority ordering, exponential backoff retries, and delayed execution.

---

## Queue API Usage

```php
use WPAIOS\Modules\Automation\Queue\WorkflowQueue;

$queue = new WorkflowQueue();

// Enqueue a high-priority workflow (priority 1 = highest)
$item = $queue->enqueue(
    workflowId: 'wp_ai_os_create_landing_page',
    input: ['title' => 'Product Launch Landing Page'],
    priority: 1,
    maxAttempts: 3
);

// Dequeue next ready item
$nextItem = $queue->dequeue();

if ($nextItem) {
    // Process workflow item...
    $queue->complete($nextItem->id);
}
```
