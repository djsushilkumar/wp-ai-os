# Media Code Examples — WP AI OS

## PHP Uploading Media via Service

```php
use WPAIOS\Modules\Media\Services\UploadManager;

$uploadManager = $container->get(UploadManager::class);

$item = $uploadManager->uploadFile(
    filePath: '/tmp/banner.png',
    title: 'Hero Banner',
    altText: 'Modern Hero Banner'
);

echo "Uploaded Attachment ID: " . $item->id . "\n";
```

---

## MCP Ability Tool Call

```json
{
    "tool": "wp_ai_os_media_upload",
    "arguments": {
        "action": "upload",
        "file_path": "/var/www/html/assets/logo.png",
        "title": "Company Logo",
        "alt_text": "Official Company Logo"
    }
}
```
