<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Abilities;

use PHPUnit\Framework\TestCase;
use WPAIOS\Contracts\LoggerInterface;
use WPAIOS\Modules\Abilities\Discovery\AbilityDiscoveryManager;
use WPAIOS\Modules\Abilities\Execution\AbilityExecutionEngine;
use WPAIOS\Modules\Abilities\Registry\AbilityRegistry;
use WPAIOS\Modules\Abilities\Types\System\SiteInfoAbility;
use WPAIOS\Modules\Mcp\Tools\ToolRegistry;

class AbilityFrameworkTest extends TestCase
{
    public function testAbilityExecution(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $engine = new AbilityExecutionEngine($logger);

        $ability = new SiteInfoAbility();
        $result = $engine->execute($ability, []);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
    }

    public function testAbilityDiscoverySyncToMcp(): void
    {
        $abilityRegistry = new AbilityRegistry();
        $toolRegistry = new ToolRegistry();
        $logger = $this->createMock(LoggerInterface::class);

        $ability = new SiteInfoAbility();
        $abilityRegistry->register($ability);

        $discovery = new AbilityDiscoveryManager($abilityRegistry, $toolRegistry, $logger);
        $synced = $discovery->syncToMcp();

        $this->assertEquals(1, $synced);
        $this->assertTrue($toolRegistry->has('wp_ai_os_site_info'));
    }
}
