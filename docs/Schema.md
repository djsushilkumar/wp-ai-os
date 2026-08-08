# Schema.org JSON-LD Specification — WP AI OS

## Supported Schema Types

1. **Organization** (`SchemaBuilder::buildOrganization()`)
2. **Article / BlogPosting** (`SchemaBuilder::buildArticle()`)
3. **FAQPage** (`SchemaBuilder::buildFAQ()`)

---

## Example Organization Schema Output

```json
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "WP AI OS",
    "url": "https://wp-ai-os.io",
    "logo": "https://wp-ai-os.io/logo.png"
}
```
