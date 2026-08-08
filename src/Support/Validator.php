<?php

declare(strict_types=1);

namespace WPAIOS\Support;

use WPAIOS\Contracts\ValidatorInterface;

/**
 * Data Validator utility supporting common validation rules (required, email, numeric, string, array, url).
 */
class Validator implements ValidatorInterface
{
    /**
     * @var array<string, array<string>>
     */
    private array $validationErrors = [];

    /**
     * Validate data against rules.
     *
     * @param array<string, mixed> $data
     * @param array<string, string|array<string>> $rules
     * @return bool
     */
    public function validate(array $data, array $rules): bool
    {
        $this->validationErrors = [];

        foreach ($rules as $field => $fieldRules) {
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }

        return empty($this->validationErrors);
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        if ($rule === 'required' && (null === $value || '' === $value)) {
            $this->addError($field, sprintf('Field [%s] is required.', $field));
        }

        if (null !== $value && '' !== $value) {
            if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->addError($field, sprintf('Field [%s] must be a valid email.', $field));
            }

            if ($rule === 'numeric' && !is_numeric($value)) {
                $this->addError($field, sprintf('Field [%s] must be numeric.', $field));
            }

            if ($rule === 'string' && !is_string($value)) {
                $this->addError($field, sprintf('Field [%s] must be a string.', $field));
            }

            if ($rule === 'array' && !is_array($value)) {
                $this->addError($field, sprintf('Field [%s] must be an array.', $field));
            }

            if ($rule === 'url' && !filter_var($value, FILTER_VALIDATE_URL)) {
                $this->addError($field, sprintf('Field [%s] must be a valid URL.', $field));
            }
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->validationErrors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->validationErrors;
    }
}
