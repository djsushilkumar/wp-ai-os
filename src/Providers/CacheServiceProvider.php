<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

use WPAIOS\Contracts\CacheInterface;
use WPAIOS\Core\Cache\CacheManager;
use WPAIOS\Core\Cache\Drivers\MemoryCacheDriver;
use WPAIOS\Core\Cache\Drivers\TransientCacheDriver;

/**
 * Cache Service Provider binding CacheManager into DI Container.
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(CacheInterface::class, function () {
            $transient = new TransientCacheDriver();
            $memory = new MemoryCacheDriver();
            return new CacheManager($transient, $memory);
        });

        $this->container->alias('cache', CacheInterface::class);
    }
}
