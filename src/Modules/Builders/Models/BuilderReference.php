<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderReference
 */
class BuilderReference
{
    public function __construct(
        private string $refType,
        private string|int $refId,
        private array $meta = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'ref_type' => $this->refType,
            'ref_id' => $this->refId,
            'meta' => $this->meta,
        ];
    }
}
