<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Schema Registry validating input parameters against JSON Schemas.
 */
class SchemaRegistry
{
    /**
     * Validate data against JSON schema structure array.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $schema
     * @return bool
     */
    public function validate(array $data, array $schema): bool
    {
        if (!isset($schema['properties']) || !is_array($schema['properties'])) {
            return true;
        }

        $required = $schema['required'] ?? [];
        if (is_array($required)) {
            foreach ($required as $reqField) {
                if (!array_key_exists($reqField, $data) || null === $data[$reqField]) {
                    return false;
                }
            }
        }

        return true;
    }
}
