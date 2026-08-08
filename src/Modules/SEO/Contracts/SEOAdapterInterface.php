<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Contracts;

use WPAIOS\Modules\SEO\Models\SEOMetadataModel;

/**
 * SEO Adapter Interface — contract for third-party SEO plugin drivers.
 */
interface SEOAdapterInterface
{
    public function id(): string;
    public function name(): string;

    public function detect(): bool;

    public function getMetadata(int $postId): ?SEOMetadataModel;

    public function updateMetadata(int $postId, SEOMetadataModel $metadata): bool;

    /**
     * @param int $postId
     * @return array<string, mixed>
     */
    public function getSchema(int $postId): array;

    /**
     * @param int $postId
     * @param array<string, mixed> $schemaData
     * @return bool
     */
    public function updateSchema(int $postId, array $schemaData): bool;
}
