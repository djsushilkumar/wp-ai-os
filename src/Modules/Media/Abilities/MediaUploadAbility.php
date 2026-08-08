<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Abilities;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\Services\UploadManager;

/**
 * Media Upload Ability — exposes media uploading, deletion, and listing to MCP agents.
 */
class MediaUploadAbility extends AbstractAbility
{
    protected string $category = 'Media';
    protected array $permissions = ['upload_files'];

    public function __construct(
        private UploadManager $uploadManager,
        private MediaRepositoryInterface $repository
    ) {
    }

    public function id(): string
    {
        return 'wp_ai_os_media_upload';
    }

    public function name(): string
    {
        return 'Media Library Upload & Delete Manager';
    }

    public function description(): string
    {
        return 'Upload local files into the WordPress Media Library, delete attachments, or list library items.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['upload', 'delete', 'get', 'list']],
                'file_path' => ['type' => 'string', 'description' => 'Local server path to file for upload.'],
                'attachment_id' => ['type' => 'integer'],
                'title' => ['type' => 'string'],
                'alt_text' => ['type' => 'string'],
            ],
            'required' => ['action'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $attachmentId = (int) ($params['attachment_id'] ?? 0);

        return match ($action) {
            'upload' => $this->uploadManager->uploadFile(
                $params['file_path'] ?? '',
                $params['title'] ?? '',
                $params['alt_text'] ?? ''
            )->toArray(),
            'get' => $this->repository->find($attachmentId)?->toArray(),
            'delete' => ['success' => $this->repository->delete($attachmentId, true)],
            'list' => array_map(fn ($item) => $item->toArray(), $this->repository->query($params)),
            default => throw new Exception("Unknown media upload action: {$action}"),
        };
    }
}
