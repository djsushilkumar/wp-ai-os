<?php

declare(strict_types=1);

namespace WPAIOS\Services;

use Exception;
use WPAIOS\Contracts\ConfigInterface;
use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Contracts\ModuleInterface;

/**
 * Module Loader managing registration, activation, and boot cycles of platform modules.
 */
class ModuleLoader
{
    /**
     * @var array<string, ModuleInterface>
     */
    private array $modules = [];

    /**
     * @param ContainerInterface $container
     * @param ConfigInterface $config
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        private ContainerInterface $container,
        private ConfigInterface $config,
        private LoggerInterface $logger,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * Register a module instance.
     *
     * @param ModuleInterface $module
     * @return void
     */
    public function registerModule(ModuleInterface $module): void
    {
        $this->modules[$module->getName()] = $module;
    }

    /**
     * Load, register, and boot all configured enabled modules.
     *
     * @return void
     */
    public function loadModules(): void
    {
        $configuredModules = $this->config->get('modules.enabled', []);

        if (is_array($configuredModules)) {
            foreach ($configuredModules as $moduleClass) {
                if (is_string($moduleClass) && class_exists($moduleClass)) {
                    try {
                        /** @var ModuleInterface $module */
                        $module = $this->container->get($moduleClass);
                        $this->registerModule($module);
                    } catch (Exception $e) {
                        $this->logger->error(sprintf('Failed to instantiate module [%s]: %s', $moduleClass, $e->getMessage()));
                    }
                }
            }
        }

        // 1. Register Phase
        foreach ($this->modules as $name => $module) {
            if ($module->isEnabled()) {
                try {
                    $module->register($this->container);
                    $this->logger->info(sprintf('Registered module [%s].', $name));
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Error registering module [%s]: %s', $name, $e->getMessage()));
                }
            }
        }

        // 2. Boot Phase
        foreach ($this->modules as $name => $module) {
            if ($module->isEnabled()) {
                try {
                    $module->boot($this->container);
                    $this->logger->info(sprintf('Booted module [%s].', $name));
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Error booting module [%s]: %s', $name, $e->getMessage()));
                }
            }
        }

        $this->eventDispatcher->dispatch('modules.loaded', $this->modules);
    }

    /**
     * Get all registered modules.
     *
     * @return array<string, ModuleInterface>
     */
    public function getModules(): array
    {
        return $this->modules;
    }
}
