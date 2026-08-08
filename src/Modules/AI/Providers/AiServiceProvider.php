<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Providers;

use WPAIOS\Contracts\EventDispatcherInterface;
use WPAIOS\Modules\AI\Context\ContextWindow;
use WPAIOS\Modules\AI\Context\TokenCounter;
use WPAIOS\Modules\AI\Providers\Drivers\AnthropicProvider;
use WPAIOS\Modules\AI\Providers\Drivers\AzureOpenAIProvider;
use WPAIOS\Modules\AI\Providers\Drivers\DeepSeekProvider;
use WPAIOS\Modules\AI\Providers\Drivers\GeminiProvider;
use WPAIOS\Modules\AI\Providers\Drivers\GroqProvider;
use WPAIOS\Modules\AI\Providers\Drivers\OllamaProvider;
use WPAIOS\Modules\AI\Providers\Drivers\OpenAIProvider;
use WPAIOS\Modules\AI\Providers\Drivers\OpenRouterProvider;
use WPAIOS\Modules\AI\Providers\Drivers\VertexAIProvider;
use WPAIOS\Modules\AI\Security\KeyEncryptor;
use WPAIOS\Modules\AI\Security\KeyStorage;
use WPAIOS\Providers\AbstractServiceProvider;

/**
 * AI Framework Service Provider binding LLM drivers, ProviderRegistry, KeyStorage, and Context Window managers into Container.
 */
class AiServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        // 1. Security & Storage
        $this->container->singleton(KeyEncryptor::class);
        $this->container->singleton(KeyStorage::class, function () {
            return new KeyStorage($this->container->get(KeyEncryptor::class));
        });

        // 2. Context & Token Management
        $this->container->singleton(TokenCounter::class);
        $this->container->singleton(ContextWindow::class, function () {
            return new ContextWindow($this->container->get(TokenCounter::class));
        });

        // 3. Provider Registry & LLM Drivers
        $this->container->singleton(ProviderRegistry::class, function () {
            $registry = new ProviderRegistry($this->container->get(EventDispatcherInterface::class));
            /** @var KeyStorage $storage */
            $storage = $this->container->get(KeyStorage::class);

            // Register Drivers
            $registry->register(new GeminiProvider(['api_key' => $storage->getKey('gemini')]));
            $registry->register(new OpenAIProvider(['api_key' => $storage->getKey('openai')]));
            $registry->register(new AnthropicProvider(['api_key' => $storage->getKey('anthropic')]));
            $registry->register(new OpenRouterProvider(['api_key' => $storage->getKey('openrouter')]));
            $registry->register(new GroqProvider(['api_key' => $storage->getKey('groq')]));
            $registry->register(new DeepSeekProvider(['api_key' => $storage->getKey('deepseek')]));
            $registry->register(new OllamaProvider(['endpoint' => 'http://127.0.0.1:11434']));
            $registry->register(new AzureOpenAIProvider());
            $registry->register(new VertexAIProvider());

            $registry->setFallbackChain(['openai', 'anthropic', 'gemini', 'openrouter', 'groq', 'deepseek', 'ollama']);
            return $registry;
        });
    }
}
