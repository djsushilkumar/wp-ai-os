<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class FormidableFormsAdapter
 * Adapter for Formidable Forms plugin.
 */
class FormidableFormsAdapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'formidable';
    }

    public function getName(): string
    {
        return 'Formidable Forms';
    }

    public function isAvailable(): bool
    {
        return class_exists('FrmAppHelper');
    }

    public function getVersion(): ?string
    {
        return class_exists('FrmAppHelper') && method_exists('FrmAppHelper', 'plugin_version')
            ? \FrmAppHelper::plugin_version()
            : null;
    }
}
