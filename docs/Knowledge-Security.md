# Knowledge Security & SSRF Protection

- **SSRF Guard**: Blocks localhost, `127.0.0.1`, and private IP subnets (`10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`).
- **File Guard**: Validates MIME types, extensions, and file sizes. Uploaded files are never executed.
