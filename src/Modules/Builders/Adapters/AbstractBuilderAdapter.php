<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Adapters;

use WPAIOS\Modules\Builders\Contracts\BuilderAdapterInterface;
use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Builders\Models\BuilderNode;

/**
 * Class AbstractBuilderAdapter
 * Base adapter implementation providing default memory stubs.
 */
abstract class AbstractBuilderAdapter implements BuilderAdapterInterface
{
    protected array $inMemoryDocuments = [];
    protected array $inMemoryTemplates = [];

    public function getCapabilities(): BuilderCapabilitiesModel
    {
        return new BuilderCapabilitiesModel(
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            $this->isActive(),
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true
        );
    }

    public function getDocument(int|string $pageId): ?BuilderDocument
    {
        return $this->inMemoryDocuments[$pageId] ?? null;
    }

    public function saveDocument(int|string $pageId, BuilderDocument $document): bool
    {
        $this->inMemoryDocuments[$pageId] = $document;
        return true;
    }

    public function compileToNative(BuilderDocument $document): mixed
    {
        return $document->toArray();
    }

    public function parseFromNative(mixed $nativeData): BuilderDocument
    {
        if (is_array($nativeData)) {
            $id = $nativeData['id'] ?? 'doc_' . uniqid();
            $title = $nativeData['title'] ?? 'Untitled';
            $nodesRaw = $nativeData['nodes'] ?? [];

            $nodes = [];
            foreach ($nodesRaw as $n) {
                if ($n instanceof BuilderNode) {
                    $nodes[] = $n;
                } elseif (is_array($n)) {
                    $nodes[] = new BuilderNode(
                        $n['id'] ?? 'node_' . uniqid(),
                        $n['type'] ?? 'container',
                        $n['settings'] ?? [],
                        $n['children'] ?? []
                    );
                }
            }

            return new BuilderDocument($id, $title, $nodes, $nativeData['settings'] ?? []);
        }

        return new BuilderDocument('doc_' . uniqid(), 'Parsed Document');
    }

    public function getTemplates(): array
    {
        return array_values($this->inMemoryTemplates);
    }

    public function exportTemplate(int|string $templateId): array
    {
        return $this->inMemoryTemplates[$templateId] ?? [];
    }

    public function importTemplate(array $templateData): ?int
    {
        $id = rand(100, 999);
        $this->inMemoryTemplates[$id] = $templateData;
        return $id;
    }
}
