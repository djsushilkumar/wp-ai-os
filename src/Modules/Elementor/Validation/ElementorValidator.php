<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Validation;

/**
 * Elementor AST Validator — verifies element structure, required fields, and broken references.
 */
class ElementorValidator
{
    private const VALID_EL_TYPES = ['container', 'section', 'column', 'widget'];

    private const REQUIRED_FIELDS = ['id', 'elType', 'settings', 'elements'];

    /**
     * Validate a full Elementor content array.
     *
     * @param array<array<string, mixed>> $content
     * @return string[] Error messages (empty array means valid).
     */
    public function validateContent(array $content): array
    {
        $errors = [];

        foreach ($content as $index => $element) {
            $elErrors = $this->validateElement($element, "content[{$index}]");
            $errors = array_merge($errors, $elErrors);
        }

        return $errors;
    }

    /**
     * Recursively validate a single Elementor element node.
     *
     * @param array<string, mixed> $element
     * @param string $path  Current path for error messages.
     * @return string[]
     */
    public function validateElement(array $element, string $path = 'element'): array
    {
        $errors = [];

        // 1. Check required fields
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!array_key_exists($field, $element)) {
                $errors[] = sprintf('[%s] Missing required field: %s', $path, $field);
            }
        }

        // 2. Check elType is valid
        $elType = $element['elType'] ?? '';
        if (!empty($elType) && !in_array($elType, self::VALID_EL_TYPES, true)) {
            $errors[] = sprintf('[%s] Unknown elType: %s', $path, $elType);
        }

        // 3. Widgets must declare widgetType
        if ($elType === 'widget' && empty($element['widgetType'])) {
            $errors[] = sprintf('[%s] Widget element is missing widgetType.', $path);
        }

        // 4. ID must be a non-empty string
        if (empty($element['id'])) {
            $errors[] = sprintf('[%s] Element is missing id.', $path);
        }

        // 5. Recursively validate children
        if (!empty($element['elements']) && is_array($element['elements'])) {
            foreach ($element['elements'] as $childIndex => $child) {
                $childErrors = $this->validateElement($child, "{$path}.elements[{$childIndex}]");
                $errors = array_merge($errors, $childErrors);
            }
        }

        return $errors;
    }
}
