<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Providers;

use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Discovery\AbilityDiscoveryManager;
use WPAIOS\Modules\Abilities\Execution\AbilityExecutionEngine;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Abilities\Types\Automation\CronAbility;
use WPAIOS\Modules\Abilities\Types\Developer\FilesystemAbility;
use WPAIOS\Modules\Abilities\Types\System\HealthCheckAbility;
use WPAIOS\Modules\Abilities\Types\System\PhpInfoAbility;
use WPAIOS\Modules\Abilities\Types\System\PluginInfoAbility;
use WPAIOS\Modules\Abilities\Types\System\SiteInfoAbility;
use WPAIOS\Modules\Abilities\Types\System\ThemeInfoAbility;
use WPAIOS\Modules\Abilities\Types\System\WordPressInfoAbility;
use WPAIOS\Modules\Abilities\Types\WordPress\PageManagerAbility;
use WPAIOS\Modules\Abilities\Types\WordPress\PostManagerAbility;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * Service Provider registering the Ability Framework, Ability Registry, Execution Engine, built-in abilities, and MCP Discovery sync.
 */
class AbilitiesServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(AbilityRegistry::class);

        $this->container->singleton(
            AbilityExecutionEngine::class,
            function () {
                return new AbilityExecutionEngine($this->container->get(LoggerInterface::class));
            }
        );

        $this->container->singleton(
            AbilityDiscoveryManager::class,
            function () {
                $toolRegistry = $this->container->has(ToolRegistry::class) ? $this->container->get(ToolRegistry::class) : null;
                return new AbilityDiscoveryManager(
                    $this->container->get(AbilityRegistry::class),
                    $toolRegistry,
                    $this->container->get(LoggerInterface::class)
                );
            }
        );
    }

    public function boot(): void
    {
        /** @var AbilityRegistry $registry */
        $registry = $this->container->get(AbilityRegistry::class);

        // 1. Register System Abilities
        $registry->register(new SiteInfoAbility());
        $registry->register(new PluginInfoAbility());
        $registry->register(new ThemeInfoAbility());
        $registry->register(new WordPressInfoAbility());
        $registry->register(new PhpInfoAbility());
        $registry->register(new HealthCheckAbility());

        // 2. Register WordPress Abilities
        $registry->register(new PageManagerAbility());
        $registry->register(new PostManagerAbility());

        // 3. Register Developer Abilities
        $registry->register(new FilesystemAbility());

        // 4. Register Automation Abilities
        $registry->register(new CronAbility());

        // 5. Automatically Sync Registered Abilities to MCP ToolRegistry
        /** @var AbilityDiscoveryManager $discoveryManager */
        $discoveryManager = $this->container->get(AbilityDiscoveryManager::class);
        $discoveryManager->syncToMcp();
    }
}
