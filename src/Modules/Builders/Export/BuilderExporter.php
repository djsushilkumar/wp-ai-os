<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Export;

use WPAIOS\Modules\Builders\Contracts\BuilderExporterInterface;
use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Class BuilderExporter
 */
class BuilderExporter implements BuilderExporterInterface
{
    public function export(BuilderDocument $document): array
    {
        return [
            'version' => '1.0',
            'generator' => 'WP AI OS Multi-Builder Framework',
            'document' => $document->toArray(),
        ];
    }
}
