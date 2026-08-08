<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Core\Event\EventDispatcher;

/**
 * Event Service Provider binding EventDispatcher into DI Container.
 */
class EventServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(EventDispatcherInterface::class, function () {
            return new EventDispatcher();
        });
    }
}
