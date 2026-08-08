<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderAsset
 */
class BuilderAsset
{
    public function __construct(
        private string $type,
        private string $url,
        private ?int $id = null
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'url' => $this->url,
            'id' => $this->id,
        ];
    }
}
