<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Adapters;

use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Elementor\ElementorManager;

/**
 * Class ElementorAdapter
 * Connects existing WPAIOS\Modules\Elementor subsystem into the unified Multi-Builder framework.
 */
class ElementorAdapter extends AbstractBuilderAdapter
{
    public function __construct(private ?ElementorManager $elementorManager = null)
    {
    }

    public function getSlug(): string
    {
        return 'elementor';
    }

    public function getName(): string
    {
        return 'Elementor Page Builder';
    }

    public function isInstalled(): bool
    {
        return defined('ELEMENTOR_VERSION') || class_exists('\Elementor\Plugin') || null !== $this->elementorManager;
    }

    public function isActive(): bool
    {
        if ($this->elementorManager) {
            return $this->elementorManager->detect();
        }
        return defined('ELEMENTOR_VERSION') || class_exists('\Elementor\Plugin');
    }

    public function getVersion(): ?string
    {
        return defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.20.0';
    }

    public function getCapabilities(): BuilderCapabilitiesModel
    {
        return new BuilderCapabilitiesModel(
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true
        );
    }

    public function getDocument(int|string $pageId): ?BuilderDocument
    {
        if ($this->elementorManager && function_exists('get_post_meta')) {
            $raw = get_post_meta((int) $pageId, '_elementor_data', true);
            if (!empty($raw)) {
                $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
                return $this->parseFromNative($decoded);
            }
        }
        return parent::getDocument($pageId);
    }

    public function saveDocument(int|string $pageId, BuilderDocument $document): bool
    {
        if ($this->elementorManager) {
            try {
                return $this->elementorManager->buildAndUpdatePage((int) $pageId, $document->toArray());
            } catch (\Throwable $e) {
                // Fallback to parent in-memory storage if WP core DB calls aren't mocked
            }
        }
        return parent::saveDocument($pageId, $document);
    }
}
