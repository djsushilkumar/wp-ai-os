# Agent Security & Defense in Depth

- **Zero Direct WordPress Core Access**: Agents interact exclusively through registered abilities.
- **Audit Secret Isolation**: API keys, passwords, and tokens are automatically redacted (`[REDACTED_SECRET]`).
- **Loop & Budget Caps**: Step and handoff thresholds prevent runaway token consumption.
