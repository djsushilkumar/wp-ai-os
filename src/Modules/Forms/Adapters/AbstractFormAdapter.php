<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

use WPAIOS\Modules\Forms\Contracts\FormProviderInterface;
use WPAIOS\Modules\Forms\Models\FormModel;
use WPAIOS\Modules\Forms\Models\FormSubmissionModel;
use WPAIOS\Modules\Forms\Models\ProviderCapabilitiesModel;

/**
 * Class AbstractFormAdapter
 * Base adapter providing safe default fallback implementations.
 */
abstract class AbstractFormAdapter implements FormProviderInterface
{
    protected array $inMemoryForms = [];
    protected array $inMemorySubmissions = [];

    public function getCapabilities(): ProviderCapabilitiesModel
    {
        return new ProviderCapabilitiesModel(
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            [
                'text', 'email', 'number', 'phone', 'url', 'textarea',
                'select', 'multi_select', 'radio', 'checkbox', 'date',
                'time', 'date_time', 'file_upload', 'image_upload',
                'hidden', 'password', 'rating', 'address', 'name',
                'signature', 'consent', 'captcha', 'html', 'page_break'
            ]
        );
    }

    public function getForms(): array
    {
        return array_values($this->inMemoryForms);
    }

    public function getForm(string|int $formId): ?FormModel
    {
        return $this->inMemoryForms[$formId] ?? null;
    }

    public function createForm(array $data): FormModel
    {
        $id = $data['id'] ?? ('form_' . uniqid());
        $title = $data['title'] ?? 'Untitled Form';
        $description = $data['description'] ?? '';
        $fields = $data['fields'] ?? [];

        $form = new FormModel($id, $title, $description, true, $this->getSlug(), $fields, $data['settings'] ?? []);
        $this->inMemoryForms[$id] = $form;

        return $form;
    }

    public function updateForm(string|int $formId, array $data): bool
    {
        if (!isset($this->inMemoryForms[$formId])) {
            return false;
        }

        $existing = $this->inMemoryForms[$formId];
        $updated = new FormModel(
            $formId,
            $data['title'] ?? $existing->getTitle(),
            $data['description'] ?? $existing->getDescription(),
            $data['enabled'] ?? $existing->isEnabled(),
            $this->getSlug(),
            $data['fields'] ?? $existing->getFields(),
            $data['settings'] ?? $existing->getSettings()
        );

        $this->inMemoryForms[$formId] = $updated;
        return true;
    }

    public function duplicateForm(string|int $formId): ?FormModel
    {
        $existing = $this->getForm($formId);
        if (!$existing) {
            return null;
        }

        $newId = 'form_' . uniqid();
        $copy = new FormModel(
            $newId,
            $existing->getTitle() . ' (Copy)',
            $existing->getDescription(),
            $existing->isEnabled(),
            $this->getSlug(),
            $existing->getFields(),
            $existing->getSettings()
        );

        $this->inMemoryForms[$newId] = $copy;
        return $copy;
    }

    public function deleteForm(string|int $formId): bool
    {
        if (isset($this->inMemoryForms[$formId])) {
            unset($this->inMemoryForms[$formId]);
            return true;
        }
        return false;
    }

    public function enableForm(string|int $formId): bool
    {
        return $this->updateForm($formId, ['enabled' => true]);
    }

    public function disableForm(string|int $formId): bool
    {
        return $this->updateForm($formId, ['enabled' => false]);
    }

    public function exportForm(string|int $formId): array
    {
        $form = $this->getForm($formId);
        return $form ? $form->toArray() : [];
    }

    public function importForm(array $exportData): ?FormModel
    {
        if (empty($exportData['title'])) {
            return null;
        }
        return $this->createForm($exportData);
    }

    public function getSubmissions(string|int $formId, int $limit = 20, int $offset = 0, array $filters = []): array
    {
        $submissions = array_filter(
            $this->inMemorySubmissions,
            fn ($sub) => (string) $sub->getFormId() === (string) $formId
        );
        return array_slice(array_values($submissions), $offset, $limit);
    }

    public function getSubmission(string|int $submissionId): ?FormSubmissionModel
    {
        foreach ($this->inMemorySubmissions as $sub) {
            if ((string) $sub->getId() === (string) $submissionId) {
                return $sub;
            }
        }
        return null;
    }

    public function createSubmission(string|int $formId, array $entryData): ?FormSubmissionModel
    {
        $subId = 'sub_' . uniqid();
        $sub = new FormSubmissionModel($subId, $formId, $entryData);
        $this->inMemorySubmissions[] = $sub;
        return $sub;
    }

    public function deleteSubmission(string|int $submissionId): bool
    {
        foreach ($this->inMemorySubmissions as $k => $sub) {
            if ((string) $sub->getId() === (string) $submissionId) {
                unset($this->inMemorySubmissions[$k]);
                return true;
            }
        }
        return false;
    }
}
