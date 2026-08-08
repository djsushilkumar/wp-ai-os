<?php

declare(strict_types=1);

namespace WPAIOS\Core\Module;

use Exception;
use WPAIOS\Contracts\ConfigInterface;
use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Contracts\ModuleInterface;

/**
 * Enterprise Module Manager controlling registration, boot, dependency graph resolution, enablement, and lifecycle management.
 */
class ModuleManager
{
    /**
     * @var array<string, ModuleInterface>
     */
    private array $modules = [];

    /**
     * @param ContainerInterface       $container
     * @param ConfigInterface          $config
     * @param LoggerInterface          $logger
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
        $this->modules[ $module->getName() ] = $module;
    }

    /**
     * Enable a module by name.
     *
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function enableModule(string $name): void
    {
        if (! isset($this->modules[ $name ])) {
            throw new Exception(sprintf('Module [%s] is not registered.', $name));
        }

        $module = $this->modules[ $name ];
        if ($module instanceof AbstractModule) {
            $module->setEnabled(true);
        }

        $this->eventDispatcher->dispatch('module.enabled', $name);
    }

    /**
     * Disable a module by name.
     *
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function disableModule(string $name): void
    {
        if (! isset($this->modules[ $name ])) {
            throw new Exception(sprintf('Module [%s] is not registered.', $name));
        }

        $module = $this->modules[ $name ];
        if ($module instanceof AbstractModule) {
            $module->setEnabled(false);
        }

        $this->eventDispatcher->dispatch('module.disabled', $name);
    }

    /**
     * Load, resolve dependency graph, register, and boot all modules.
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

        // Resolve topological dependency order
        $orderedModules = $this->resolveDependencyOrder();

        // 1. Register Phase
        foreach ($orderedModules as $module) {
            if ($module->isEnabled()) {
                try {
                    $module->register($this->container);
                    $this->logger->info(sprintf('Registered module [%s].', $module->getName()));
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Error registering module [%s]: %s', $module->getName(), $e->getMessage()));
                }
            }
        }

        // 2. Boot Phase
        foreach ($orderedModules as $module) {
            if ($module->isEnabled()) {
                try {
                    $module->boot($this->container);
                    $this->logger->info(sprintf('Booted module [%s].', $module->getName()));
                } catch (Exception $e) {
                    $this->logger->error(sprintf('Error booting module [%s]: %s', $module->getName(), $e->getMessage()));
                }
            }
        }

        $this->eventDispatcher->dispatch('modules.loaded', $this->modules);
    }

    /**
     * Resolve module dependency graph using topological sort.
     *
     * @return ModuleInterface[]
     */
    public function resolveDependencyOrder(): array
    {
        $resolved = [];
        $visited  = [];

        $visit = function (ModuleInterface $module) use (&$visit, &$resolved, &$visited): void {
            $name = $module->getName();
            if (isset($visited[ $name ])) {
                return;
            }
            $visited[ $name ] = true;

            if ($module instanceof AbstractModule) {
                foreach ($module->getDependencies() as $depName) {
                    if (isset($this->modules[ $depName ])) {
                        $visit($this->modules[ $depName ]);
                    }
                }
            }

            $resolved[] = $module;
        };

        foreach ($this->modules as $module) {
            $visit($module);
        }

        return $resolved;
    }

    /**
     * Get registered module by name.
     *
     * @param string $name
     * @return ModuleInterface|null
     */
    public function getModule(string $name): ?ModuleInterface
    {
        return $this->modules[ $name ] ?? null;
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
