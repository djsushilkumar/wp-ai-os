<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Models;

use WPAIOS\Modules\Forms\Contracts\FormInterface;

/**
 * Class FormModel
 * Value object representing a normalized form.
 */
class FormModel implements FormInterface
{
    /**
     * @param FormFieldModel[] $fields
     */
    public function __construct(
        private string|int $id,
        private string $title,
        private string $description = '',
        private bool $enabled = true,
        private string $providerSlug = 'unknown',
        private array $fields = [],
        private array $settings = []
    ) {
    }

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getProviderSlug(): string
    {
        return $this->providerSlug;
    }

    /**
     * @return FormFieldModel[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'provider_slug' => $this->providerSlug,
            'fields' => array_map(fn ($f) => $f instanceof FormFieldModel ? $f->toArray() : $f, $this->fields),
            'settings' => $this->settings,
        ];
    }
}
