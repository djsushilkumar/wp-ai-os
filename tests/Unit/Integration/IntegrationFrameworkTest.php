<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Integration;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Integration\Adapters\ElementorAdapter;
use WPAIOS\Modules\Integration\Adapters\GutenbergAdapter;
use WPAIOS\Modules\Integration\Adapters\WooCommerceAdapter;
use WPAIOS\Modules\Integration\Compatibility\CompatibilityChecker;
use WPAIOS\Modules\Integration\Discovery\PluginDiscoveryManager;
use WPAIOS\Modules\Integration\Registry\IntegrationRegistry;

class IntegrationFrameworkTest extends TestCase
{
    private IntegrationRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new IntegrationRegistry();
        $this->registry->register(new ElementorAdapter());
        $this->registry->register(new WooCommerceAdapter());
        $this->registry->register(new GutenbergAdapter());
    }

    public function testRegistryAdapterRegistration(): void
    {
        $this->assertTrue($this->registry->has('elementor'));
        $this->assertTrue($this->registry->has('woocommerce'));
        $this->assertTrue($this->registry->has('gutenberg'));

        $adapter = $this->registry->get('elementor');
        $this->assertEquals('Elementor Website Builder', $adapter->name());
    }

    public function testDiscoveryManagerScansAdapters(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $discovery = new PluginDiscoveryManager($this->registry, $logger);

        $report = $discovery->discover();

        $this->assertIsArray($report);
        $this->assertEquals(3, $report['total_registered']);
        $this->assertArrayHasKey('active_adapters', $report);
    }

    public function testCompatibilityCheckerValidatesRuntime(): void
    {
        $checker = $newChecker = new CompatibilityChecker();
        $adapter = new GutenbergAdapter();

        $result = $checker->check($adapter);

        $this->assertIsBool($result['compatible']);
    }
}
