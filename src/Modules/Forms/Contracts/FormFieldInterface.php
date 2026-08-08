<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

/**
 * Interface FormFieldInterface
 */
interface FormFieldInterface
{
    public function getId(): string;

    public function getType(): string;

    public function getLabel(): string;

    public function isRequired(): bool;

    public function getOptions(): array;

    public function getDefaultValue(): mixed;

    public function toArray(): array;
}
