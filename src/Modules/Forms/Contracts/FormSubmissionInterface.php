<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

/**
 * Interface FormSubmissionInterface
 */
interface FormSubmissionInterface
{
    public function getId(): string|int;

    public function getFormId(): string|int;

    public function getData(): array;

    public function getCreatedAt(): string;

    public function getUserIp(): ?string;

    public function toArray(): array;
}
