<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Abilities;

use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\Services\MetadataManager;

/**
 * Media Metadata Ability — exposes ALT text, caption, title, and description updates to MCP agents.
 */
class MediaMetadataAbility extends AbstractAbility
{
    protected string $category = 'Media';
    protected array $permissions = ['upload_files'];

    public function __construct(
        private MetadataManager $metadataManager,
        private MediaRepositoryInterface $repository
    ) {
    }

    public function id(): string
    {
        return 'wp_ai_os_media_metadata';
    }

    public function name(): string
    {
        return 'Media Metadata Manager';
    }

    public function description(): string
    {
        return 'Read or update ALT text, captions, titles, and descriptions for WordPress media attachments.';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'action' => ['type' => 'string', 'enum' => ['get', 'update']],
                'attachment_id' => ['type' => 'integer'],
                'title' => ['type' => 'string'],
                'alt_text' => ['type' => 'string'],
                'caption' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
            'required' => ['action', 'attachment_id'],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $attachmentId = (int) ($params['attachment_id'] ?? 0);

        if ($action === 'get') {
            return $this->repository->find($attachmentId)?->toArray();
        }

        if ($action === 'update') {
            return $this->metadataManager->updateMetadata($attachmentId, $params)->toArray();
        }

        return null;
    }
}
