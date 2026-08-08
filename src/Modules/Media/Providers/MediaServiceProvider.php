<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Media\Providers;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Media\Abilities\MediaMetadataAbility;
use WPAIOS\Modules\Media\Abilities\MediaUploadAbility;
use WPAIOS\Modules\Media\Contracts\MediaRepositoryInterface;
use WPAIOS\Modules\Media\MediaManager;
use WPAIOS\Modules\Media\Repositories\MediaRepository;
use WPAIOS\Modules\Media\Services\MetadataManager;
use WPAIOS\Modules\Media\Services\UploadManager;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider binding all Media Platform services and abilities into DI Container.
 */
class MediaServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(MediaRepositoryInterface::class, MediaRepository::class);

        $this->container->singleton(UploadManager::class, function () {
            return new UploadManager(
                $this->container->get(MediaRepositoryInterface::class),
                $this->container->get(LoggerInterface::class)
            );
        });

        $this->container->singleton(MetadataManager::class, function () {
            return new MetadataManager(
                $this->container->get(MediaRepositoryInterface::class),
                $this->container->get(LoggerInterface::class)
            );
        });

        $this->container->singleton(MediaManager::class, function () {
            return new MediaManager(
                $this->container->get(UploadManager::class),
                $this->container->get(MetadataManager::class),
                $this->container->get(LoggerInterface::class)
            );
        });
    }

    public function boot(): void
    {
        /** @var MediaManager $manager */
        $manager = $this->container->get(MediaManager::class);
        $manager->boot();

        if ($this->container->has(AbilityRegistry::class)) {
            /** @var AbilityRegistry $abilityRegistry */
            $abilityRegistry = $this->container->get(AbilityRegistry::class);

            $abilityRegistry->register(new MediaUploadAbility(
                $this->container->get(UploadManager::class),
                $this->container->get(MediaRepositoryInterface::class)
            ));
            $abilityRegistry->register(new MediaMetadataAbility(
                $this->container->get(MetadataManager::class),
                $this->container->get(MediaRepositoryInterface::class)
            ));
        }
    }
}
