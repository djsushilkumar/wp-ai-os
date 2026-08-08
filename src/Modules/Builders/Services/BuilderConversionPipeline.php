<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Services;

use WPAIOS\Modules\Builders\Contracts\BuilderAdapterInterface;
use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Class BuilderConversionPipeline
 * Pipeline: Blueprint -> Normalized Builder Document -> Builder Adapter -> Native Structure.
 */
class BuilderConversionPipeline
{
    public function convert(BuilderDocument $document, BuilderAdapterInterface $targetAdapter): mixed
    {
        return $targetAdapter->compileToNative($document);
    }
}
