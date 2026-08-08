<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Interface BuilderExporterInterface
 */
interface BuilderExporterInterface
{
    public function export(BuilderDocument $document): array;
}
