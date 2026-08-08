# Autonomous Workflow Engine Architecture — WP AI OS

## Overview

The **Autonomous Workflow Engine** in WP AI OS allows AI agents and external controllers (like Antigravity Agent IDE) to safely execute complex, multi-step WordPress operations. Every AI action is orchestrated through a structured workflow pipeline:

```
Workflow Engine
      ↓
   Planner          (Dependency Resolution via Kahn's Topological Sort & Risk Analysis)
      ↓
  Task Queue        (Persistent priority queue with exponential backoff retries)
      ↓
   Executor         (Runs individual tasks with latency tracking & retry logic)
      ↓
  Checkpoint        (Saves context snapshots after every task for resume-on-failure)
      ↓
   Rollback         (LIFO automatic rollback sequence if any task fails)
      ↓
 Execution Report   (Detailed execution metrics & audit trail)
```

---

## Key Features

1. **Dependency Resolution**: `DependencyPlanner` uses Kahn's algorithm to resolve execution order safely.
2. **Risk Analysis**: `RiskAnalyzer` rates workflow risk (`low`, `medium`, `high`, `critical`) based on operation safety.
3. **Automatic Retries**: Tasks support configurable retry attempts with exponential backoff delay.
4. **Persistent Queueing**: `WorkflowQueue` stores background workflows with priority sorting and delayed execution.
5. **Stateful Checkpointing**: `CheckpointMemory` snapshots context after each task to enable resumption.
6. **LIFO Rollback**: If a step fails, `RollbackManager` executes `rollback()` on completed tasks in reverse order.
