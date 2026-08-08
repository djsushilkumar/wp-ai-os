# SEO Code Examples — WP AI OS

## Analyzing Post SEO Health in PHP

```php
use WPAIOS\Modules\SEO\Adapters\FallbackSEOAdapter;
use WPAIOS\Modules\SEO\Services\SEOAnalyzer;

$adapter = $container->get(FallbackSEOAdapter::class);
$analyzer = $container->get(SEOAnalyzer::class);

$meta = $adapter->getMetadata($postId = 12);
$analysis = $analyzer->analyze($meta);

echo "SEO Score: " . $analysis['score'] . "/100\n";
```

---

## Updating Meta via MCP Ability

```json
{
    "tool": "wp_ai_os_seo_metadata",
    "arguments": {
        "action": "update",
        "post_id": 12,
        "meta_title": "Enterprise AI OS for WordPress",
        "meta_description": "Build and control autonomous AI agents for WordPress with WP AI OS.",
        "focus_keyword": "AI OS WordPress"
    }
}
```
