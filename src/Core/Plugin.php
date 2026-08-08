<?php

declare(strict_types=1);

namespace WPAIOS\Core;

use Exception;

/**
 * Singleton Plugin Orchestrator linking WordPress lifecycle hooks to WP AI OS Kernel.
 */
final class Plugin
{
    private static ?Plugin $instance = null;
    private ?Kernel $kernel          = null;

    private function __construct()
    {
    }

    /**
     * Get Singleton Instance.
     *
     * @return Plugin
     */
    public static function instance(): Plugin
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize plugin lifecycle.
     *
     * @param string $configPath Path to default settings array configuration.
     * @return void
     * @throws Exception
     */
    public function boot(string $configPath = ''): void
    {
        if (null !== $this->kernel) {
            return; // Already booted
        }

        $config = [];
        if (! empty($configPath) && file_exists($configPath)) {
            $config = include $configPath;
        }

        $container    = new Container();
        $this->kernel = new Kernel($container, is_array($config) ? $config : []);
        $this->kernel->boot();

        $this->registerHooks();
    }

    /**
     * Get initialized Kernel instance.
     *
     * @return Kernel|null
     */
    public function getKernel(): ?Kernel
    {
        return $this->kernel;
    }

    /**
     * Register WordPress Core action hooks.
     *
     * @return void
     */
    private function registerHooks(): void
    {
        if (function_exists('add_action')) {
            add_action('init', [ $this, 'onInit' ]);
            add_action('rest_api_init', [ $this, 'onRestApiInit' ]);
        }
    }

    /**
     * WP `init` Action Handler.
     *
     * @return void
     */
    public function onInit(): void
    {
        /** @var EventDispatcher|null $eventDispatcher */
        $eventDispatcher = $this->kernel?->container->get(EventDispatcher::class);
        $eventDispatcher?->dispatch('wp.init');
    }

    /**
     * WP `rest_api_init` Action Handler.
     *
     * @return void
     */
    public function onRestApiInit(): void
    {
        /** @var EventDispatcher|null $eventDispatcher */
        $eventDispatcher = $this->kernel?->container->get(EventDispatcher::class);
        $eventDispatcher?->dispatch('wp.rest_api_init');
    }
}
