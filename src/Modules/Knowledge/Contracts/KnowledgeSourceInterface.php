<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Contracts;

/**
 * Interface KnowledgeSourceInterface
 */
interface KnowledgeSourceInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getType(): string;

    public function getCapabilities(): array;
}
