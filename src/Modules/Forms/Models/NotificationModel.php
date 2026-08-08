<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Models;

/**
 * Class NotificationModel
 */
class NotificationModel
{
    public function __construct(
        private string $type,
        private array $recipients,
        private string $subject,
        private string $body,
        private array $extra = []
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'recipients' => $this->recipients,
            'subject' => $this->subject,
            'body' => $this->body,
            'extra' => $this->extra,
        ];
    }
}
