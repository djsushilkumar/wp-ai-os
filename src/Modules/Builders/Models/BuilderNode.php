<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

use WPAIOS\Modules\Builders\Contracts\BuilderElementInterface;

/**
 * Class BuilderNode
 * Represents a single node (container, section, block, widget) in normalized tree.
 */
class BuilderNode implements BuilderElementInterface
{
    /**
     * @param BuilderNode[] $children
     */
    public function __construct(
        private string $id,
        private string $type,
        private array $settings = [],
        private array $children = [],
        private ?BuilderStyle $style = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return BuilderNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getStyle(): ?BuilderStyle
    {
        return $this->style;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'settings' => $this->settings,
            'children' => array_map(fn ($child) => $child instanceof BuilderNode ? $child->toArray() : $child, $this->children),
            'style' => $this->style?->toArray(),
        ];
    }
}
