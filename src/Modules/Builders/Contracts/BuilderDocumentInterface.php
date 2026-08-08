<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface BuilderDocumentInterface
 */
interface BuilderDocumentInterface
{
    public function getId(): string|int;

    public function getTitle(): string;

    public function getNodes(): array;

    public function toArray(): array;
}
