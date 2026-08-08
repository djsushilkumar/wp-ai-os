<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

use WPAIOS\Modules\Forms\Models\FormSubmissionModel;

/**
 * Class FormSubmissionManager
 * Manages form submissions with PII protection and filtering.
 */
class FormSubmissionManager
{
    /**
     * Strip PII or sensitive keys before audit logging or public export.
     */
    public function sanitizeSubmissionForAudit(FormSubmissionModel $submission): array
    {
        $data = $submission->getData();
        $sensitiveKeys = ['password', 'card_number', 'cvv', 'ssn', 'secret', 'token', 'credit_card'];

        foreach ($data as $k => $v) {
            foreach ($sensitiveKeys as $sk) {
                if (str_contains(strtolower((string) $k), $sk)) {
                    $data[$k] = '[REDACTED_PII]';
                }
            }
        }

        return [
            'id' => $submission->getId(),
            'form_id' => $submission->getFormId(),
            'sanitized_data' => $data,
            'created_at' => $submission->getCreatedAt(),
        ];
    }
}
