<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Core\Cache\CacheManager;
use WPAIOS\Core\Cache\Drivers\MemoryCacheDriver;

class CacheManagerTest extends TestCase
{
    public function testMemoryCacheOperations(): void
    {
        $primary = new MemoryCacheDriver();
        $cache = new CacheManager($primary);

        $cache->set('user_key', 'user_value', 3600);
        $this->assertEquals('user_value', $cache->get('user_key'));
        $this->assertTrue($cache->has('user_key'));

        $cache->delete('user_key');
        $this->assertNull($cache->get('user_key'));
        $this->assertFalse($cache->has('user_key'));
    }

    public function testRememberMethod(): void
    {
        $cache = new CacheManager(new MemoryCacheDriver());

        $result = $cache->remember('computed_key', 3600, function () {
            return 'computed_val';
        });

        $this->assertEquals('computed_val', $result);
        $this->assertEquals('computed_val', $cache->get('computed_key'));
    }
}
