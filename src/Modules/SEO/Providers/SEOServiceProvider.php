<?php

declare(strict_types=1);

namespace WPAIOS\Modules\SEO\Providers;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\SEO\Abilities\SchemaAbility;
use WPAIOS\Modules\SEO\Abilities\SEOMetadataAbility;
use WPAIOS\Modules\SEO\Adapters\FallbackSEOAdapter;
use WPAIOS\Modules\SEO\SEOManager;
use WPAIOS\Modules\SEO\Services\SchemaBuilder;
use WPAIOS\Modules\SEO\Services\SEOAnalyzer;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider binding SEO services, adapters, schema generators, and abilities into Container.
 */
class SEOServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(FallbackSEOAdapter::class);
        $this->container->singleton(SEOAnalyzer::class);
        $this->container->singleton(SchemaBuilder::class);

        $this->container->singleton(SEOManager::class, function () {
            return new SEOManager(
                $this->container->get(FallbackSEOAdapter::class),
                $this->container->get(SEOAnalyzer::class),
                $this->container->get(LoggerInterface::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var SEOManager $manager */
        $manager = $this->container->get(SEOManager::class);
        $manager->boot();

        if ($this->container->has(AbilityRegistry::class)) {
            /** @var AbilityRegistry $abilityRegistry */
            $abilityRegistry = $this->container->get(AbilityRegistry::class);

            $abilityRegistry->register(new SEOMetadataAbility(
                $this->container->get(FallbackSEOAdapter::class),
                $this->container->get(SEOAnalyzer::class)
            ));
            $abilityRegistry->register(new SchemaAbility(
                $this->container->get(FallbackSEOAdapter::class),
                $this->container->get(SchemaBuilder::class)
            ));
        }
    }
}
