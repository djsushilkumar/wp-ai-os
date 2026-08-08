<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Models;

/**
 * Class ProviderCapabilitiesModel
 * Feature matrix capabilities for a form provider adapter.
 */
class ProviderCapabilitiesModel
{
    public function __construct(
        private bool $supportsFormCreation = true,
        private bool $supportsFormEditing = true,
        private bool $supportsFormDeletion = true,
        private bool $supportsFormDuplication = true,
        private bool $supportsSubmissions = true,
        private bool $supportsExportImport = true,
        private bool $supportsFileUpload = true,
        private bool $supportsMultiPage = true,
        private bool $supportsConditionalLogic = true,
        private array $supportedFieldTypes = []
    ) {
    }

    public function canCreateForms(): bool
    {
        return $this->supportsFormCreation;
    }

    public function canEditForms(): bool
    {
        return $this->supportsFormEditing;
    }

    public function canDeleteForms(): bool
    {
        return $this->supportsFormDeletion;
    }

    public function canDuplicateForms(): bool
    {
        return $this->supportsFormDuplication;
    }

    public function canManageSubmissions(): bool
    {
        return $this->supportsSubmissions;
    }

    public function canExportImport(): bool
    {
        return $this->supportsExportImport;
    }

    public function getSupportedFieldTypes(): array
    {
        return $this->supportedFieldTypes;
    }

    public function toArray(): array
    {
        return [
            'supports_create' => $this->supportsFormCreation,
            'supports_edit' => $this->supportsFormEditing,
            'supports_delete' => $this->supportsFormDeletion,
            'supports_duplicate' => $this->supportsFormDuplication,
            'supports_submissions' => $this->supportsSubmissions,
            'supports_export_import' => $this->supportsExportImport,
            'supports_file_upload' => $this->supportsFileUpload,
            'supports_multi_page' => $this->supportsMultiPage,
            'supports_conditional_logic' => $this->supportsConditionalLogic,
            'supported_field_types' => $this->supportedFieldTypes,
        ];
    }
}
