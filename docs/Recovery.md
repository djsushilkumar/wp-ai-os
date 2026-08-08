# Recovery & Checkpoint Specification — WP AI OS

## Checkpoint System

The `CheckpointMemory` snapshots the `WorkflowContext` state into persistent storage after every successful task completion. If a workflow fails or the server restarts mid-execution, the system can resume from the last saved checkpoint without repeating completed steps.

---

## Rollback Subsystem

When a task fails after exhausting all retries, the `RollbackManager` is invoked automatically:

1. Identifies all tasks that completed prior to failure.
2. Filters for tasks where `isRollbackable() === true`.
3. Calls `$task->rollback($context)` in LIFO (reverse) order.
4. Logs all rollback actions to the execution report.
