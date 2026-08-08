<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

/**
 * Interface BuilderTemplateInterface
 */
interface BuilderTemplateInterface
{
    public function getId(): string|int;

    public function getTitle(): string;

    public function getType(): string;

    public function getContent(): array;
}
