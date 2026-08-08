<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers;

use Exception;
use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Modules\AI\Contracts\AIProviderInterface;
use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;

/**
 * Enterprise Provider Registry managing LLM drivers and circuit-breaker fallback routing.
 */
class ProviderRegistry
{
    /**
     * @var array<string, AIProviderInterface>
     */
    private array $providers = [];

    /**
     * @var string[]
     */
    private array $fallbackChain = [];

    private string $defaultProvider = 'openai';

    public function __construct(private ?EventDispatcherInterface $eventDispatcher = null)
    {
    }

    public function register(AIProviderInterface $provider): void
    {
        $this->providers[$provider->getName()] = $provider;
    }

    public function setDefaultProvider(string $name): void
    {
        $this->defaultProvider = $name;
    }

    public function setFallbackChain(array $chain): void
    {
        $this->fallbackChain = $chain;
    }

    public function get(string $name): AIProviderInterface
    {
        if (!isset($this->providers[$name])) {
            throw new Exception(sprintf('AI Provider [%s] is not registered.', $name));
        }

        return $this->providers[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->providers[$name]);
    }

    /**
     * Execute completion request with automatic circuit-breaker fallback.
     *
     * @param Request $request
     * @param string|null $preferredProvider
     * @return Response
     * @throws Exception
     */
    public function executeWithFallback(Request $request, ?string $preferredProvider = null): Response
    {
        $chain = $this->fallbackChain;

        if ($preferredProvider && isset($this->providers[$preferredProvider])) {
            array_unshift($chain, $preferredProvider);
            $chain = array_unique($chain);
        }

        if (empty($chain)) {
            $chain = array_keys($this->providers);
        }

        $lastException = null;

        foreach ($chain as $providerName) {
            if (!isset($this->providers[$providerName])) {
                continue;
            }

            try {
                $provider = $this->providers[$providerName];
                $this->eventDispatcher?->dispatch('ai.before_request', $providerName, $request);

                $response = $provider->chat($request);

                $this->eventDispatcher?->dispatch('ai.after_response', $providerName, $response);
                return $response;
            } catch (Exception $e) {
                $lastException = $e;
                $this->eventDispatcher?->dispatch('ai.failover', $providerName, $e->getMessage());
            }
        }

        throw new Exception('All AI Providers in fallback chain failed. Last error: ' . ($lastException?->getMessage() ?? 'Unknown error'));
    }

    /**
     * @return array<string, AIProviderInterface>
     */
    public function all(): array
    {
        return $this->providers;
    }
}
