<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

use WPAIOS\Modules\Forms\Models\FormModel;
use WPAIOS\Modules\Forms\Models\FormSubmissionModel;

/**
 * Interface FormRepositoryInterface
 */
interface FormRepositoryInterface
{
    /**
     * @return FormModel[]
     */
    public function findAll(?string $provider = null): array;

    public function findById(string|int $id, ?string $provider = null): ?FormModel;

    public function save(FormModel $form, ?string $provider = null): FormModel;

    public function delete(string|int $id, ?string $provider = null): bool;

    /**
     * @return FormSubmissionModel[]
     */
    public function findSubmissions(string|int $formId, int $limit = 20, int $offset = 0, array $filters = [], ?string $provider = null): array;
}
