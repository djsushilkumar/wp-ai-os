<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Models;

use WPAIOS\Modules\Forms\Contracts\FormSubmissionInterface;

/**
 * Class FormSubmissionModel
 */
class FormSubmissionModel implements FormSubmissionInterface
{
    public function __construct(
        private string|int $id,
        private string|int $formId,
        private array $data,
        private string $createdAt = '',
        private ?string $userIp = null,
        private ?string $userAgent = null
    ) {
        if (empty($this->createdAt)) {
            $this->createdAt = gmdate('Y-m-d H:i:s');
        }
    }

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getFormId(): string|int
    {
        return $this->formId;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'form_id' => $this->formId,
            'data' => $this->data,
            'created_at' => $this->createdAt,
            'user_ip' => $this->userIp,
            'user_agent' => $this->userAgent,
        ];
    }
}
