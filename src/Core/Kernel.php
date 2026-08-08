<?php

declare(strict_types=1);

namespace WPAIOS\Core;

use Exception;
use WPAIOS\Providers\Anthropic\AnthropicProvider;
use WPAIOS\Providers\Gemini\GeminiProvider;
use WPAIOS\Providers\Ollama\OllamaProvider;
use WPAIOS\Providers\OpenAI\OpenAIProvider;
use WPAIOS\Providers\ProviderRegistry;

/**
 * WP AI OS Central Kernel handling dependency initialization and subsystem boots.
 */
class Kernel
{
    private bool $booted = false;

    /**
     * @param Container            $container
     * @param array<string, mixed> $config
     */
    public function __construct(
        public readonly Container $container,
        public readonly array $config = []
    ) {
    }

    /**
     * Boot all kernel services.
     *
     * @return void
     * @throws Exception
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        // 1. Register Core Services
        $this->container->singleton(Container::class, $this->container);
        $this->container->singleton(EventDispatcher::class, new EventDispatcher());

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $this->container->get(EventDispatcher::class);

        // 2. Initialize Provider Registry & Drivers
        $providerRegistry = new ProviderRegistry($eventDispatcher);

        $providerConfigs = $this->config['providers'] ?? [];

        if (isset($providerConfigs['openai'])) {
            $providerRegistry->register(new OpenAIProvider($providerConfigs['openai']));
        }
        if (isset($providerConfigs['anthropic'])) {
            $providerRegistry->register(new AnthropicProvider($providerConfigs['anthropic']));
        }
        if (isset($providerConfigs['gemini'])) {
            $providerRegistry->register(new GeminiProvider($providerConfigs['gemini']));
        }
        if (isset($providerConfigs['ollama'])) {
            $providerRegistry->register(new OllamaProvider($providerConfigs['ollama']));
        }

        if (isset($providerConfigs['fallback_chain'])) {
            $providerRegistry->setFallbackChain($providerConfigs['fallback_chain']);
        }
        if (isset($providerConfigs['default'])) {
            $providerRegistry->setDefaultProvider($providerConfigs['default']);
        }

        $this->container->singleton(ProviderRegistry::class, $providerRegistry);

        // 3. Mark Boot Complete
        $this->booted = true;

        $eventDispatcher->dispatch('kernel.booted', $this);
    }

    /**
     * Check if kernel has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }
}
