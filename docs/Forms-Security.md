# Forms Security & Privacy Guide

- **Capability Enforcement**: All REST and MCP tool operations require `manage_options`.
- **PII Scrubbing**: Submissions are automatically sanitized before audit logging (`[REDACTED_PII]`).
- **GDPR Compliance**: Support for submission export and deletion (`wp_ai_os_forms_submissions_delete`).
