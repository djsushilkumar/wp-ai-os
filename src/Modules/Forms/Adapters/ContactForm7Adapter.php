<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Adapters;

/**
 * Class ContactForm7Adapter
 * Adapter for Contact Form 7 plugin.
 */
class ContactForm7Adapter extends AbstractFormAdapter
{
    public function getSlug(): string
    {
        return 'cf7';
    }

    public function getName(): string
    {
        return 'Contact Form 7';
    }

    public function isAvailable(): bool
    {
        return class_exists('WPCF7') || function_exists('wpcf7');
    }

    public function getVersion(): ?string
    {
        return defined('WPCF7_VERSION') ? WPCF7_VERSION : null;
    }
}
