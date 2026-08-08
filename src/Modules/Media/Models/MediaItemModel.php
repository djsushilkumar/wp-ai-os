<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Models;

/**
 * Normalized Media Item Value Object for WordPress Attachments.
 */
class MediaItemModel
{
    /**
     * @param int $id Attachment post ID
     * @param string $title
     * @param string $altText
     * @param string $caption
     * @param string $description
     * @param string $mimeType
     * @param string $url
     * @param string $filePath
     * @param int $fileSize
     * @param int|null $width
     * @param int|null $height
     */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $altText = '',
        public readonly string $caption = '',
        public readonly string $description = '',
        public readonly string $mimeType = 'image/jpeg',
        public readonly string $url = '',
        public readonly string $filePath = '',
        public readonly int $fileSize = 0,
        public readonly ?int $width = null,
        public readonly ?int $height = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alt_text' => $this->altText,
            'caption' => $this->caption,
            'description' => $this->description,
            'mime_type' => $this->mimeType,
            'url' => $this->url,
            'file_path' => $this->filePath,
            'file_size' => $this->fileSize,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
