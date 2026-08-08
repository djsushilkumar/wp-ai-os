<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\ConfigInterface;
use WPAIOS\Contracts\ContainerInterface;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Contracts\ModuleInterface;
use WPAIOS\Core\Container;

class MockModule implements ModuleInterface
{
    public bool $registered = false;
    public bool $booted = false;

    public function getName(): string
    {
        return 'mock_module';
    }

    public function getTitle(): string
    {
        return 'Mock Module';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function register(ContainerInterface $container): void
    {
        $this->registered = true;
    }

    public function boot(ContainerInterface $container): void
    {
        $this->booted = true;
    }
}

class ModuleLoaderTest extends TestCase
{
    public function testModuleRegistrationAndBoot(): void
    {
        $container = new Container();
        $config = $this->createMock(ConfigInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $moduleLoader = new \WPAIOS\Services\ModuleLoader($container, $config, $logger, $dispatcher);
        $mockModule = new MockModule();

        $moduleLoader->registerModule($mockModule);
        $moduleLoader->loadModules();

        $this->assertTrue($mockModule->registered);
        $this->assertTrue($mockModule->booted);
    }
}
