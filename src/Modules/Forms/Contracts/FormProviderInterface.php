<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

use WPAIOS\Modules\Forms\Models\FormModel;
use WPAIOS\Modules\Forms\Models\FormSubmissionModel;
use WPAIOS\Modules\Forms\Models\ProviderCapabilitiesModel;

/**
 * Interface FormProviderInterface
 *
 * Defines the contract for all WordPress form plugin adapters.
 */
interface FormProviderInterface
{
    public function getSlug(): string;

    public function getName(): string;

    public function isAvailable(): bool;

    public function getVersion(): ?string;

    public function getCapabilities(): ProviderCapabilitiesModel;

    /**
     * @return FormModel[]
     */
    public function getForms(): array;

    public function getForm(string|int $formId): ?FormModel;

    public function createForm(array $data): FormModel;

    public function updateForm(string|int $formId, array $data): bool;

    public function duplicateForm(string|int $formId): ?FormModel;

    public function deleteForm(string|int $formId): bool;

    public function enableForm(string|int $formId): bool;

    public function disableForm(string|int $formId): bool;

    public function exportForm(string|int $formId): array;

    public function importForm(array $exportData): ?FormModel;

    /**
     * @return FormSubmissionModel[]
     */
    public function getSubmissions(string|int $formId, int $limit = 20, int $offset = 0, array $filters = []): array;

    public function getSubmission(string|int $submissionId): ?FormSubmissionModel;

    public function createSubmission(string|int $formId, array $entryData): ?FormSubmissionModel;

    public function deleteSubmission(string|int $submissionId): bool;
}
