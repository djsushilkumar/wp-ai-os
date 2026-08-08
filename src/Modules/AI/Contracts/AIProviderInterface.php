<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Contracts;

use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;

/**
 * Standard Contract for AI Model Providers.
 */
interface AIProviderInterface
{
    public function getName(): string;

    /**
     * Check if provider supports a feature ('chat', 'streaming', 'vision', 'tools', 'embeddings', 'audio', 'image_gen').
     *
     * @param string $feature
     * @return bool
     */
    public function supportsFeature(string $feature): bool;

    public function chat(Request $request): Response;
    public function stream(Request $request, callable $callback): Response;
}
