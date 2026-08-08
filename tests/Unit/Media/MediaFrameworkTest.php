<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Media;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Media\Models\MediaItemModel;
use WPAIOS\Modules\Media\Repositories\MediaRepository;
use WPAIOS\Modules\Media\Services\UploadManager;

class MediaFrameworkTest extends TestCase
{
    public function testMediaItemModelSerialization(): void
    {
        $item = new MediaItemModel(
            id: 88,
            title: 'Sample Hero Banner',
            altText: 'Hero Banner Image',
            caption: 'Main website hero banner',
            mimeType: 'image/webp',
            url: 'https://example.com/wp-content/uploads/hero.webp',
            fileSize: 1048576,
            width: 1920,
            height: 1080
        );

        $array = $item->toArray();

        $this->assertEquals(88, $array['id']);
        $this->assertEquals('Sample Hero Banner', $array['title']);
        $this->assertEquals('Hero Banner Image', $array['alt_text']);
        $this->assertEquals('image/webp', $array['mime_type']);
        $this->assertEquals(1920, $array['width']);
    }

    public function testUploadManagerInstantiation(): void
    {
        $repository = $this->createMock(MediaRepository::class);
        $logger = $this->createMock(LoggerInterface::class);

        $uploadManager = new UploadManager($repository, $logger);
        $this->assertInstanceOf(UploadManager::class, $uploadManager);
    }
}
