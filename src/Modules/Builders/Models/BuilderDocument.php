<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

use WPAIOS\Modules\Builders\Contracts\BuilderDocumentInterface;

/**
 * Class BuilderDocument
 * Normalized internal document representation for a page layout.
 */
class BuilderDocument implements BuilderDocumentInterface
{
    /**
     * @param BuilderNode[] $nodes
     */
    public function __construct(
        private string|int $id,
        private string $title,
        private array $nodes = [],
        private array $settings = [],
        private array $meta = []
    ) {
    }

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return BuilderNode[]
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'nodes' => array_map(fn ($node) => $node instanceof BuilderNode ? $node->toArray() : $node, $this->nodes),
            'settings' => $this->settings,
            'meta' => $this->meta,
        ];
    }
}
