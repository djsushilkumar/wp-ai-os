# Human Approval Manager

- Critical operations generate an Approval Request (`ApprovalManager`).
- Execution pauses until an administrator calls `agents/approvals/approve`.
- Rejection halts the task safely without mutating site state.
