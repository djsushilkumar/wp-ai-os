<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\AI;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\AI\Models\Message;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;
use WPAIOS\Modules\AI\Providers\AbstractAIProvider;
use WPAIOS\Modules\AI\Providers\ProviderRegistry;

class MockSuccessAiProvider extends AbstractAIProvider
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
        return new Response(content: 'Mock AI Response', model: 'mock-1.0');
    }

    public function stream(Request $request, callable $callback): Response
    {
        return $this->chat($request);
    }
}

class ProviderRegistryTest extends TestCase
{
    public function testExecuteWithFallback(): void
    {
        $registry = new ProviderRegistry();
        $provider = new MockSuccessAiProvider();

        $registry->register($provider);
        $registry->setFallbackChain(['mock_success']);

        $request = new Request(messages: [new Message('user', 'Hello')]);
        $response = $registry->executeWithFallback($request);

        $this->assertEquals('Mock AI Response', $response->content);
        $this->assertEquals('mock-1.0', $response->model);
    }
}
