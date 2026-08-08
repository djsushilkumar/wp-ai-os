<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Providers;

use WPAIOS\Core\Container;
use WPAIOS\Modules\Forms\Adapters\ContactForm7Adapter;
use WPAIOS\Modules\Forms\Adapters\FallbackFormAdapter;
use WPAIOS\Modules\Forms\Adapters\FluentFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\FormidableFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\GravityFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\NinjaFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\WPFormsAdapter;
use WPAIOS\Modules\Forms\FormsManager;
use WPAIOS\Modules\Forms\Repositories\FormRepository;
use WPAIOS\Modules\Forms\Services\FormDiscovery;
use WPAIOS\Modules\Forms\Services\FormValidator;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Class FormsServiceProvider
 * Binds Forms module dependencies into DI container.
 */
class FormsServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(FormDiscovery::class, function () {
            $discovery = new FormDiscovery();
            $discovery->registerAdapter(new FluentFormsAdapter());
            $discovery->registerAdapter(new GravityFormsAdapter());
            $discovery->registerAdapter(new WPFormsAdapter());
            $discovery->registerAdapter(new ContactForm7Adapter());
            $discovery->registerAdapter(new NinjaFormsAdapter());
            $discovery->registerAdapter(new FormidableFormsAdapter());
            $discovery->registerAdapter(new FallbackFormAdapter());
            return $discovery;
        });

        $this->container->singleton(FormValidator::class, fn () => new FormValidator());

        $this->container->singleton(FormRepository::class, function (Container $c) {
            return new FormRepository($c->get(FormDiscovery::class));
        });

        $this->container->singleton(FormsManager::class, function (Container $c) {
            return new FormsManager(
                $c->get(FormDiscovery::class),
                $c->get(FormRepository::class),
                $c->get(FormValidator::class)
            );
        });
    }

    public function boot(): void
    {
        // Hook activation & initialization if needed
    }
}
