<?php

declare(strict_types=1);

namespace WPAIOS\Providers;

use WPAIOS\Providers\Models\Request;
use WPAIOS\Providers\Models\Response;

/**
 * Standard Contract for AI Model Providers.
 */
interface ProviderInterface
{
    /**
     * Get unique provider identifier name (e.g., 'openai', 'anthropic').
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Check if provider supports a specific feature capability (e.g., 'tools', 'streaming', 'vision').
     *
     * @param string $feature
     * @return bool
     */
    public function supportsFeature(string $feature): bool;

    /**
     * Send a completion request to the AI provider.
     *
     * @param Request $request
     * @return Response
     */
    public function chat(Request $request): Response;

    /**
     * Stream a completion response via callback chunk handler.
     *
     * @param Request $request
     * @param callable(string $chunk): void $callback
     * @return Response
     */
    public function stream(Request $request, callable $callback): Response;
}
