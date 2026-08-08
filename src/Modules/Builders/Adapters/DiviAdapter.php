<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Adapters;

use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;

/**
 * Class DiviAdapter
 * Adapter for Elegant Themes Divi Builder. Uses documented shortcode/AST integration.
 */
class DiviAdapter extends AbstractBuilderAdapter
{
    public function getSlug(): string
    {
        return 'divi';
    }

    public function getName(): string
    {
        return 'Divi Builder';
    }

    public function isInstalled(): bool
    {
        return defined('ET_BUILDER_VERSION') || class_exists('ET_Builder_Element');
    }

    public function isActive(): bool
    {
        return $this->isInstalled();
    }

    public function getVersion(): ?string
    {
        return defined('ET_BUILDER_VERSION') ? ET_BUILDER_VERSION : null;
    }

    public function getCapabilities(): BuilderCapabilitiesModel
    {
        $active = $this->isActive();
        return new BuilderCapabilitiesModel(
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            $active,
            false,
            $active,
            $active,
            $active
        );
    }
}
