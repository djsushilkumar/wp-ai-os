<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

/**
 * Interface FormInterface
 */
interface FormInterface
{
    public function getId(): string|int;

    public function getTitle(): string;

    public function getDescription(): string;

    public function isEnabled(): bool;

    public function getProviderSlug(): string;

    public function getFields(): array;

    public function toArray(): array;
}
