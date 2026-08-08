<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface BuilderElementInterface
 */
interface BuilderElementInterface
{
    public function getId(): string;

    public function getType(): string;

    public function getSettings(): array;

    public function getChildren(): array;
}
