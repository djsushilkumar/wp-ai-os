<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Interface BuilderValidatorInterface
 */
interface BuilderValidatorInterface
{
    public function validate(BuilderDocument $document): array;
}
