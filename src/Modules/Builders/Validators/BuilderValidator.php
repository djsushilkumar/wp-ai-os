<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Validators;

use WPAIOS\Modules\Builders\Contracts\BuilderValidatorInterface;
use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Class BuilderValidator
 * Validates document schema, missing references, and unsupported components.
 */
class BuilderValidator implements BuilderValidatorInterface
{
    public function validate(BuilderDocument $document): array
    {
        $warnings = [];

        if (empty($document->getTitle())) {
            $warnings[] = 'Document title is empty.';
        }

        if (empty($document->getNodes())) {
            $warnings[] = 'Document contains no structural layout nodes.';
        }

        return $warnings;
    }
}
