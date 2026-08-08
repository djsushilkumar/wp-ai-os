# Builder Security Guidelines

- **Untrusted Input**: All imported layout JSON payloads are treated as untrusted input and sanitized via `sanitize_text_field` and `sanitize_key`.
- **No Code Execution**: Never executes arbitrary PHP or JavaScript code embedded inside imported template structures.
- **Capability Controls**: All MCP builder abilities enforce `manage_options` capability checks.
