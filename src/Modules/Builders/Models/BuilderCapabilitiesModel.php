<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderCapabilitiesModel
 * Feature matrix for page builders.
 */
class BuilderCapabilitiesModel
{
    public function __construct(
        private bool $createPage = true,
        private bool $updatePage = true,
        private bool $deletePage = true,
        private bool $duplicatePage = true,
        private bool $createTemplate = true,
        private bool $updateTemplate = true,
        private bool $export = true,
        private bool $import = true,
        private bool $preview = true,
        private bool $responsiveDesign = true,
        private bool $globalStyles = true,
        private bool $customCss = true,
        private bool $dynamicContent = true,
        private bool $themeBuilder = true,
        private bool $headerBuilder = true,
        private bool $footerBuilder = true,
        private bool $popupBuilder = true,
        private bool $forms = true,
        private bool $media = true,
        private bool $reusableComponents = true
    ) {
    }

    public function toArray(): array
    {
        return [
            'create_page' => $this->createPage,
            'update_page' => $this->updatePage,
            'delete_page' => $this->deletePage,
            'duplicate_page' => $this->duplicatePage,
            'create_template' => $this->createTemplate,
            'update_template' => $this->updateTemplate,
            'export' => $this->export,
            'import' => $this->import,
            'preview' => $this->preview,
            'responsive_design' => $this->responsiveDesign,
            'global_styles' => $this->globalStyles,
            'custom_css' => $this->customCss,
            'dynamic_content' => $this->dynamicContent,
            'theme_builder' => $this->themeBuilder,
            'header_builder' => $this->headerBuilder,
            'footer_builder' => $this->footerBuilder,
            'popup_builder' => $this->popupBuilder,
            'forms' => $this->forms,
            'media' => $this->media,
            'reusable_components' => $this->reusableComponents,
        ];
    }
}
