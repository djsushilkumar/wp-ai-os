<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

/**
 * Class FieldManager
 * Manages field type mappings and field definition normalization.
 */
class FieldManager
{
    private array $supportedTypes = [
        'text', 'email', 'number', 'phone', 'url', 'textarea',
        'select', 'multi_select', 'radio', 'checkbox', 'date',
        'time', 'date_time', 'file_upload', 'image_upload',
        'hidden', 'password', 'rating', 'address', 'name',
        'signature', 'consent', 'captcha', 'html', 'page_break'
    ];

    public function getSupportedTypes(): array
    {
        return $this->supportedTypes;
    }

    public function normalizeType(string $providerType): string
    {
        $map = [
            'single_text' => 'text',
            'paragraph' => 'textarea',
            'dropdown' => 'select',
            'multiselect' => 'multi_select',
            'file' => 'file_upload',
            'image' => 'image_upload',
        ];

        return $map[strtolower($providerType)] ?? (in_array(strtolower($providerType), $this->supportedTypes, true) ? strtolower($providerType) : 'text');
    }
}
