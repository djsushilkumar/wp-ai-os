<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

/**
 * Core Application Service Provider registering core platform dependencies.
 */
class AppServiceProvider extends AbstractServiceProvider
{
    /**
     * Register core bindings in container.
     *
     * @return void
     */
    public function register(): void
    {
        // Core bindings registered during bootstrap app.php
    }

    /**
     * Boot core application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Core bootstrap hooks
    }
}
