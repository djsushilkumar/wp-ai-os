<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;
use WPAIOS\Providers\ProviderInterface;
use WPAIOS\Providers\ProviderRegistry;

class MockSuccessProvider implements ProviderInterface
{
    public function getName(): string
    {
        return 'mock_success';
    }

    public function supportsFeature(string $feature): bool
    {
        return true;
    }

    public function chat(Request $request): Response
    {
        return new Response(content: 'Success Response from Mock');
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}

class MockFailingProvider implements ProviderInterface
{
    public function getName(): string
    {
        return 'mock_failing';
    }

    public function supportsFeature(string $feature): bool
    {
        return true;
    }

    public function chat(Request $request): Response
    {
        throw new Exception('Mock provider API connection failed.');
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}

class ProviderRegistryTest extends TestCase
{
    public function testRegisterAndRetrieveProvider(): void
    {
        $registry = new ProviderRegistry();
        $provider = new MockSuccessProvider();

        $registry->register($provider);

        $this->assertSame($provider, $registry->get('mock_success'));
    }

    public function testExecuteWithFallbackCircuitBreaker(): void
    {
        $registry = new ProviderRegistry();
        $failing = new MockFailingProvider();
        $success = new MockSuccessProvider();

        $registry->register($failing);
        $registry->register($success);

        $registry->setFallbackChain(['mock_failing', 'mock_success']);

        $request = new Request(messages: [['role' => 'user', 'content' => 'Hello']]);
        $response = $registry->executeWithFallback($request);

        $this->assertEquals('Success Response from Mock', $response->content);
    }
}
