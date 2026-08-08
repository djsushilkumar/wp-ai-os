<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Support\Arr;
use WPAIOS\Support\Json;
use WPAIOS\Support\Str;

class SupportTest extends TestCase
{
    public function testStringHelpers(): void
    {
        $str = new Str();
        $this->assertEquals('hello_world', $str->snake('helloWorld'));
        $this->assertEquals('helloWorld', $str->camel('hello_world'));
        $this->assertTrue($str->startsWith('wp-ai-os', 'wp-'));
        $this->assertTrue($str->endsWith('wp-ai-os', '-os'));
    }

    public function testArrayHelpers(): void
    {
        $arr = new Arr();
        $data = ['user' => ['profile' => ['name' => 'John']]];

        $this->assertEquals('John', $arr->get($data, 'user.profile.name'));
        $this->assertEquals('default', $arr->get($data, 'user.profile.missing', 'default'));
    }

    public function testJsonHelpers(): void
    {
        $json = new Json();
        $encoded = $json->encode(['foo' => 'bar']);
        $this->assertEquals('{"foo":"bar"}', $encoded);

        $decoded = $json->decode($encoded);
        $this->assertEquals(['foo' => 'bar'], $decoded);
    }
}
