<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

/**
 * Interface NotificationInterface
 */
interface NotificationInterface
{
    public function send(string $type, array $recipient, string $subject, string $body, array $extra = []): bool;
}
