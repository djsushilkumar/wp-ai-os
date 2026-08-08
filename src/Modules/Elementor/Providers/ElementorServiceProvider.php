<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Providers;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Elementor\Builders\ContainerBuilder;
use WPAIOS\Modules\Elementor\Builders\PageBuilder;
use WPAIOS\Modules\Elementor\Builders\SectionBuilder;
use WPAIOS\Modules\Elementor\Builders\TemplateBuilder;
use WPAIOS\Modules\Elementor\ElementorManager;
use WPAIOS\Modules\Elementor\IO\ExportManager;
use WPAIOS\Modules\Elementor\IO\ImportManager;
use WPAIOS\Modules\Elementor\Page\PageApi;
use WPAIOS\Modules\Elementor\Page\RevisionManager;
use WPAIOS\Modules\Elementor\Style\GlobalStyleManager;
use WPAIOS\Modules\Elementor\Style\ResponsiveManager;
use WPAIOS\Modules\Elementor\Style\StyleEngine;
use WPAIOS\Modules\Elementor\Validation\ElementorValidator;
use WPAIOS\Modules\Elementor\Widgets\Drivers\ButtonWidget;
use WPAIOS\Modules\Elementor\Widgets\Drivers\HeadingWidget;
use WPAIOS\Modules\Elementor\Widgets\Drivers\ImageWidget;
use WPAIOS\Modules\Elementor\Widgets\Drivers\TextEditorWidget;
use WPAIOS\Modules\Elementor\Widgets\WidgetRegistry;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Elementor Service Provider — binds all Elementor automation subsystems into the DI Container.
 */
class ElementorServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // 1. Validators
        $this->container->singleton(ElementorValidator::class);

        // 2. Style & Responsive subsystems
        $this->container->singleton(StyleEngine::class);
        $this->container->singleton(ResponsiveManager::class);
        $this->container->singleton(GlobalStyleManager::class);

        // 3. Widget Registry
        $this->container->singleton(WidgetRegistry::class);

        // 4. Builders
        $this->container->singleton(ContainerBuilder::class);
        $this->container->singleton(SectionBuilder::class);
        $this->container->singleton(TemplateBuilder::class);
        $this->container->singleton(PageBuilder::class, function () {
            return new PageBuilder(
                $this->container->get(ContainerBuilder::class),
                $this->container->get(SectionBuilder::class),
                $this->container->get(StyleEngine::class)
            );
        });

        // 5. Page API & Revisions
        $this->container->singleton(PageApi::class, function () {
            return new PageApi($this->container->get(LoggerInterface::class));
        });
        $this->container->singleton(RevisionManager::class, function () {
            return new RevisionManager($this->container->get(LoggerInterface::class));
        });

        // 6. Import / Export
        $this->container->singleton(ExportManager::class);
        $this->container->singleton(ImportManager::class, function () {
            return new ImportManager(
                $this->container->get(ElementorValidator::class),
                $this->container->get(LoggerInterface::class)
            );
        });

        // 7. Central Facade
        $this->container->singleton(ElementorManager::class, function () {
            return new ElementorManager(
                pageApi: $this->container->get(PageApi::class),
                pageBuilder: $this->container->get(PageBuilder::class),
                containerBuilder: $this->container->get(ContainerBuilder::class),
                sectionBuilder: $this->container->get(SectionBuilder::class),
                templateBuilder: $this->container->get(TemplateBuilder::class),
                widgetRegistry: $this->container->get(WidgetRegistry::class),
                styleEngine: $this->container->get(StyleEngine::class),
                globalStyleManager: $this->container->get(GlobalStyleManager::class),
                responsiveManager: $this->container->get(ResponsiveManager::class),
                revisionManager: $this->container->get(RevisionManager::class),
                exportManager: $this->container->get(ExportManager::class),
                importManager: $this->container->get(ImportManager::class),
                validator: $this->container->get(ElementorValidator::class),
                logger: $this->container->get(LoggerInterface::class)
            );
        });
    }

    public function boot(): void
    {
        // Register built-in widget builders
        /** @var WidgetRegistry $registry */
        $registry = $this->container->get(WidgetRegistry::class);
        $registry->register(new HeadingWidget());
        $registry->register(new TextEditorWidget());
        $registry->register(new ButtonWidget());
        $registry->register(new ImageWidget());

        // Detect Elementor & log status
        /** @var ElementorManager $manager */
        $manager = $this->container->get(ElementorManager::class);
        $manager->detect();
    }
}
