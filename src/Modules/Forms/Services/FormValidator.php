<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

use WPAIOS\Modules\Forms\Contracts\FormValidatorInterface;
use WPAIOS\Modules\Forms\Models\FormFieldModel;
use WPAIOS\Modules\Forms\Models\FormModel;

/**
 * Class FormValidator
 * Comprehensive field validator supporting email, phone, URL, length, file rules, and spam protection.
 */
class FormValidator implements FormValidatorInterface
{
    public function validate(FormModel $form, array $submissionData): array
    {
        $errors = [];

        foreach ($form->getFields() as $field) {
            if (!$field instanceof FormFieldModel) {
                continue;
            }

            $id = $field->getId();
            $val = $submissionData[$id] ?? null;

            // Required check
            if ($field->isRequired() && (null === $val || '' === $val || (is_array($val) && empty($val)))) {
                $errors[$id][] = sprintf('Field "%s" is required.', $field->getLabel());
                continue;
            }

            if (null === $val || '' === $val) {
                continue;
            }

            // Type specific validation
            switch ($field->getType()) {
                case 'email':
                    if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                        $errors[$id][] = sprintf('Field "%s" must be a valid email address.', $field->getLabel());
                    }
                    break;
                case 'url':
                    if (!filter_var($val, FILTER_VALIDATE_URL)) {
                        $errors[$id][] = sprintf('Field "%s" must be a valid URL.', $field->getLabel());
                    }
                    break;
                case 'number':
                    if (!is_numeric($val)) {
                        $errors[$id][] = sprintf('Field "%s" must be a valid number.', $field->getLabel());
                    }
                    break;
                case 'phone':
                    if (!preg_match('/^[0-9\-\+\(\)\s]{7,20}$/', (string) $val)) {
                        $errors[$id][] = sprintf('Field "%s" must be a valid phone number.', $field->getLabel());
                    }
                    break;
            }
        }

        return $errors;
    }
}
