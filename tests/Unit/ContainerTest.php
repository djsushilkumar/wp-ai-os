<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Core\Container;

class DummyService
{
    public function getValue(): string
    {
        return 'foo';
    }
}

class DependentService
{
    public function __construct(public readonly DummyService $dummy)
    {
    }
}

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testBindAndGet(): void
    {
        $this->container->bind(DummyService::class);
        $this->assertTrue($this->container->has(DummyService::class));

        /** @var DummyService $service */
        $service = $this->container->get(DummyService::class);
        $this->assertInstanceOf(DummyService::class, $service);
        $this->assertEquals('foo', $service->getValue());
    }

    public function testSingleton(): void
    {
        $this->container->singleton(DummyService::class);

        $obj1 = $this->container->get(DummyService::class);
        $obj2 = $this->container->get(DummyService::class);

        $this->assertSame($obj1, $obj2);
    }

    public function testAutoWiring(): void
    {
        /** @var DependentService $dependent */
        $dependent = $this->container->get(DependentService::class);

        $this->assertInstanceOf(DependentService::class, $dependent);
        $this->assertInstanceOf(DummyService::class, $dependent->dummy);
    }
}
