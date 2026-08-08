<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

use Exception;
use WPAIOS\Core\EventDispatcher;
use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;

/**
 * Provider Registry managing AI Drivers and automated circuit-breaker fallback routing.
 */
class ProviderRegistry
{
    /**
     * @var array<string, ProviderInterface>
     */
    private array $providers = [];

    /**
     * @var string[]
     */
    private array $fallbackChain = [];

    private string $defaultProvider = 'openai';

    public function __construct(private ?EventDispatcher $eventDispatcher = null)
    {
    }

    /**
     * Register a provider instance.
     *
     * @param ProviderInterface $provider
     * @return void
     */
    public function register(ProviderInterface $provider): void
    {
        $this->providers[$provider->getName()] = $provider;
    }

    /**
     * Set the ordered fallback execution chain.
     *
     * @param string[] $chain
     * @return void
     */
    public function setFallbackChain(array $chain): void
    {
        $this->fallbackChain = $chain;
    }

    /**
     * Set default provider name.
     *
     * @param string $name
     * @return void
     */
    public function setDefaultProvider(string $name): void
    {
        $this->defaultProvider = $name;
    }

    /**
     * Get a specific provider by name.
     *
     * @param string $name
     * @return ProviderInterface
     * @throws Exception
     */
    public function get(string $name): ProviderInterface
    {
        if (!isset($this->providers[$name])) {
            throw new Exception(sprintf('AI Provider [%s] is not registered.', $name));
        }

        return $this->providers[$name];
    }

    /**
     * Execute completion request using primary provider with fallback execution chain.
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

                if (null !== $this->eventDispatcher) {
                    $this->eventDispatcher->dispatch('provider.before_request', $providerName, $request);
                }

                $response = $provider->chat($request);

                if (null !== $this->eventDispatcher) {
                    $this->eventDispatcher->dispatch('provider.after_response', $providerName, $response);
                }

                return $response;
            } catch (Exception $e) {
                $lastException = $e;
                if (null !== $this->eventDispatcher) {
                    $this->eventDispatcher->dispatch('provider.failover', $providerName, $e->getMessage());
                }
            }
        }

        throw new Exception('All AI Providers in fallback chain failed. Last error: ' . ($lastException?->getMessage() ?? 'Unknown error'));
    }
}
