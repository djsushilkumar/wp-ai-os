<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Models;

use WPAIOS\Modules\Forms\Contracts\FormFieldInterface;

/**
 * Class FormFieldModel
 * Represents a single normalized form field.
 */
class FormFieldModel implements FormFieldInterface
{
    public function __construct(
        private string $id,
        private string $type,
        private string $label,
        private bool $required = false,
        private array $options = [],
        private mixed $defaultValue = null,
        private array $validationRules = []
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'required' => $this->required,
            'options' => $this->options,
            'default_value' => $this->defaultValue,
            'validation_rules' => $this->validationRules,
        ];
    }
}
