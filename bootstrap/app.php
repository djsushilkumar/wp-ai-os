<?php

declare(strict_types=1);

use WPAIOS\Core\Container;
use WPAIOS\Core\EventDispatcher;
use WPAIOS\Providers\AppServiceProvider;
use WPAIOS\Services\ConfigLoader;
use WPAIOS\Services\Logger;
use WPAIOS\Services\ModuleLoader;
use WPAIOS\Services\UpgradeManager;
use WPAIOS\Services\VersionManager;

// 1. Instantiate Core Dependency Injection Container
$container = new Container();

// 2. Register Config Loader
$configPath = WPAI_OS_PATH . 'config';
$configLoader = new ConfigLoader($configPath);
$container->singleton(\WPAIOS\Contracts\ConfigInterface::class, $configLoader);
$container->instance('config', $configLoader);

// 3. Register Event Dispatcher
$eventDispatcher = new EventDispatcher();
$container->singleton(\WPAIOS\Contracts\EventDispatcherInterface::class, $eventDispatcher);
$container->instance('events', $eventDispatcher);

// 4. Register Logger
$logger = new Logger(WPAI_OS_PATH . 'logs/wp-ai-os.log');
$container->singleton(\WPAIOS\Contracts\LoggerInterface::class, $logger);
$container->instance('logger', $logger);

// 5. Register Version & Upgrade Managers
$versionManager = new VersionManager(WPAI_OS_VERSION);
$upgradeManager = new UpgradeManager($versionManager, $configLoader, $logger);
$container->singleton(VersionManager::class, $versionManager);
$container->singleton(UpgradeManager::class, $upgradeManager);

// 6. Check and Trigger System Upgrades if Version Changed
$upgradeManager->checkAndUpgrade();

// 7. Register App Core Service Provider
$appServiceProvider = new AppServiceProvider($container);
$appServiceProvider->register();

// 8. Instantiate and Boot Module Loader
$moduleLoader = new ModuleLoader($container, $configLoader, $logger, $eventDispatcher);
$container->singleton(ModuleLoader::class, $moduleLoader);
$moduleLoader->loadModules();

// 9. Boot Service Providers
$appServiceProvider->boot();

// 10. Dispatch Kernel Booted Event
$eventDispatcher->dispatch('wp_ai_os.kernel_booted', $container);

return $container;
