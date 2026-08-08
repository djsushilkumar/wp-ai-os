<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Interface BuilderAdapterInterface
 * Contract for translating between normalized BuilderDocument and native builder formats.
 */
interface BuilderAdapterInterface extends BuilderInterface
{
    public function getDocument(int|string $pageId): ?BuilderDocument;

    public function saveDocument(int|string $pageId, BuilderDocument $document): bool;

    public function compileToNative(BuilderDocument $document): mixed;

    public function parseFromNative(mixed $nativeData): BuilderDocument;

    public function getTemplates(): array;

    public function exportTemplate(int|string $templateId): array;

    public function importTemplate(array $templateData): ?int;
}
