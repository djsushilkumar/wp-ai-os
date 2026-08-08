<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Contracts;

use WPAIOS\Modules\Forms\Models\FormModel;

/**
 * Interface FormValidatorInterface
 */
interface FormValidatorInterface
{
    public function validate(FormModel $form, array $submissionData): array;
}
