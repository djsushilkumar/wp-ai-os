<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

use WPAIOS\Modules\Forms\Models\FormFieldModel;
use WPAIOS\Modules\Forms\Models\FormModel;

/**
 * Class FormFactory
 * Factory for creating FormModel and FormFieldModel instances.
 */
class FormFactory
{
    public static function createForm(array $data, string $providerSlug = 'wp_ai_os_native'): FormModel
    {
        $id = $data['id'] ?? ('form_' . uniqid());
        $title = $data['title'] ?? 'Untitled Form';
        $description = $data['description'] ?? '';
        $enabled = $data['enabled'] ?? true;
        $fieldsRaw = $data['fields'] ?? [];
        $settings = $data['settings'] ?? [];

        $fields = [];
        foreach ($fieldsRaw as $f) {
            if ($f instanceof FormFieldModel) {
                $fields[] = $f;
            } elseif (is_array($f)) {
                $fields[] = self::createField($f);
            }
        }

        return new FormModel($id, $title, $description, (bool) $enabled, $providerSlug, $fields, $settings);
    }

    public static function createField(array $data): FormFieldModel
    {
        return new FormFieldModel(
            (string) ($data['id'] ?? ('field_' . uniqid())),
            (string) ($data['type'] ?? 'text'),
            (string) ($data['label'] ?? 'Untitled Field'),
            (bool) ($data['required'] ?? false),
            (array) ($data['options'] ?? []),
            $data['default_value'] ?? null,
            (array) ($data['validation_rules'] ?? [])
        );
    }
}
