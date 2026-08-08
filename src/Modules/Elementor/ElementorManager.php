<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor;

use Exception;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Elementor\Builders\ContainerBuilder;
use WPAIOS\Modules\Elementor\Builders\PageBuilder;
use WPAIOS\Modules\Elementor\Builders\SectionBuilder;
use WPAIOS\Modules\Elementor\Builders\TemplateBuilder;
use WPAIOS\Modules\Elementor\IO\ExportManager;
use WPAIOS\Modules\Elementor\IO\ImportManager;
use WPAIOS\Modules\Elementor\Page\PageApi;
use WPAIOS\Modules\Elementor\Page\RevisionManager;
use WPAIOS\Modules\Elementor\Style\GlobalStyleManager;
use WPAIOS\Modules\Elementor\Style\ResponsiveManager;
use WPAIOS\Modules\Elementor\Style\StyleEngine;
use WPAIOS\Modules\Elementor\Validation\ElementorValidator;
use WPAIOS\Modules\Elementor\Widgets\WidgetRegistry;

/**
 * ElementorManager — central facade coordinating all Elementor automation subsystems.
 */
class ElementorManager
{
    private bool $elementorDetected = false;

    public function __construct(
        public readonly PageApi $pageApi,
        public readonly PageBuilder $pageBuilder,
        public readonly ContainerBuilder $containerBuilder,
        public readonly SectionBuilder $sectionBuilder,
        public readonly TemplateBuilder $templateBuilder,
        public readonly WidgetRegistry $widgetRegistry,
        public readonly StyleEngine $styleEngine,
        public readonly GlobalStyleManager $globalStyleManager,
        public readonly ResponsiveManager $responsiveManager,
        public readonly RevisionManager $revisionManager,
        public readonly ExportManager $exportManager,
        public readonly ImportManager $importManager,
        public readonly ElementorValidator $validator,
        public readonly LoggerInterface $logger
    ) {
    }

    /**
     * Detect if Elementor plugin is active and set internal flag.
     *
     * @return bool
     */
    public function detect(): bool
    {
        $this->elementorDetected = defined('ELEMENTOR_VERSION') || class_exists('\Elementor\Plugin');

        if (!$this->elementorDetected) {
            $this->logger->warning('[ElementorManager] Elementor plugin not detected. Running in standalone AST mode.');
        } else {
            $this->logger->info(sprintf('[ElementorManager] Elementor detected (version %s).', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'unknown'));
        }

        return $this->elementorDetected;
    }

    public function isElementorActive(): bool
    {
        return $this->elementorDetected;
    }

    /**
     * Build a page from a JSON definition, save snapshot, and write to DB.
     *
     * @param string $title
     * @param array<string, mixed> $pageDefinition
     * @param string $status
     * @return int  New post ID.
     * @throws Exception
     */
    public function buildAndCreatePage(string $title, array $pageDefinition, string $status = 'publish'): int
    {
        $ast = $this->pageBuilder->buildFromDefinition($pageDefinition);

        $postId = $this->pageApi->createPage($title, $ast, $status);
        $this->revisionManager->snapshot($postId);

        return $postId;
    }

    /**
     * Safely update an existing page (auto-snapshot before mutating).
     *
     * @param int $postId
     * @param array<string, mixed> $pageDefinition
     * @param string|null $title
     * @param string|null $status
     * @return bool
     * @throws Exception
     */
    public function buildAndUpdatePage(int $postId, array $pageDefinition, ?string $title = null, ?string $status = null): bool
    {
        $this->revisionManager->snapshot($postId);
        $ast = $this->pageBuilder->buildFromDefinition($pageDefinition);

        return $this->pageApi->updatePage($postId, $ast, $title, $status);
    }
}
