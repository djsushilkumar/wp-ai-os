<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Services\VersionManager;

class VersionManagerTest extends TestCase
{
    public function testVersionManagerGetVersion(): void
    {
        $versionManager = new VersionManager('1.0.0');
        $this->assertEquals('1.0.0', $versionManager->getVersion());
    }

    public function testNeedsUpgrade(): void
    {
        $versionManager = new VersionManager('1.1.0');
        // When getInstalledVersion returns '0.0.0' or '1.0.0'
        $this->assertTrue($versionManager->needsUpgrade());
    }
}
