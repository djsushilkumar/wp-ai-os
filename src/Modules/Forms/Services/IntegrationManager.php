<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

/**
 * Class IntegrationManager
 * Provides Elementor embedding contracts and shortcode generator without tight coupling.
 */
class IntegrationManager
{
    public function getShortcode(string $providerSlug, string|int $formId): string
    {
        return match ($providerSlug) {
            'fluentform' => sprintf('[fluentform id="%s"]', $formId),
            'gravityforms' => sprintf('[gravityform id="%s" title="false" description="false"]', $formId),
            'wpforms' => sprintf('[wpforms id="%s"]', $formId),
            'cf7' => sprintf('[contact-form-7 id="%s"]', $formId),
            'ninja_forms' => sprintf('[ninja_form id="%s"]', $formId),
            'formidable' => sprintf('[formidable id="%s"]', $formId),
            default => sprintf('[wp_ai_os_form id="%s"]', $formId),
        };
    }

    public function generateElementorWidgetData(string $providerSlug, string|int $formId): array
    {
        return [
            'id' => 'widget_' . uniqid(),
            'elType' => 'widget',
            'widgetType' => 'shortcode',
            'settings' => [
                'shortcode' => $this->getShortcode($providerSlug, $formId),
            ],
        ];
    }
}
