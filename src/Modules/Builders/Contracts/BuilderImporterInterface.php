<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Contracts;

use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Interface BuilderImporterInterface
 */
interface BuilderImporterInterface
{
    public function import(array $data): BuilderDocument;
}
