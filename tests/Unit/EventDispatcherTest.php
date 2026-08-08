<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Core\EventDispatcher;

class EventDispatcherTest extends TestCase
{
    public function testEventDispatching(): void
    {
        $dispatcher = new EventDispatcher();
        $called = false;
        $receivedPayload = null;

        $dispatcher->listen('user.created', function ($payload) use (&$called, &$receivedPayload) {
            $called = true;
            $receivedPayload = $payload;
        });

        $dispatcher->dispatch('user.created', 'admin_user');

        $this->assertTrue($called);
        $this->assertEquals('admin_user', $receivedPayload);
    }

    public function testFiltering(): void
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->listen('content.title', function ($title) {
            return strtoupper($title);
        });

        $result = $dispatcher->filter('content.title', 'hello world');

        $this->assertEquals('HELLO WORLD', $result);
    }
}
