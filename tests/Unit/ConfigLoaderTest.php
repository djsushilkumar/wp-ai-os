<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Services\ConfigLoader;

class ConfigLoaderTest extends TestCase
{
    private string $tempConfigDir;

    protected function setUp(): void
    {
        $this->tempConfigDir = sys_get_temp_dir() . '/wp_ai_os_config_test_' . uniqid();
        mkdir($this->tempConfigDir);

        file_put_contents(
            $this->tempConfigDir . '/app.php',
            "<?php return ['name' => 'WP AI OS', 'nested' => ['key' => 'value']];"
        );
    }

    protected function tearDown(): void
    {
        @unlink($this->tempConfigDir . '/app.php');
        @rmdir($this->tempConfigDir);
    }

    public function testGetDotNotation(): void
    {
        $loader = new ConfigLoader($this->tempConfigDir);

        $this->assertEquals('WP AI OS', $loader->get('app.name'));
        $this->assertEquals('value', $loader->get('app.nested.key'));
        $this->assertEquals('default', $loader->get('app.missing', 'default'));
    }

    public function testSetDotNotation(): void
    {
        $loader = new ConfigLoader($this->tempConfigDir);
        $loader->set('app.new_key', 'new_value');

        $this->assertEquals('new_value', $loader->get('app.new_key'));
    }
}
