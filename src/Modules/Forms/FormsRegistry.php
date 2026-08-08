<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms;

use WPAIOS\Modules\Forms\Contracts\FormProviderInterface;

/**
 * Class FormsRegistry
 */
class FormsRegistry
{
    private array $providers = [];

    public function register(FormProviderInterface $provider): void
    {
        $this->providers[$provider->getSlug()] = $provider;
    }

    public function get(string $slug): ?FormProviderInterface
    {
        return $this->providers[$slug] ?? null;
    }

    public function all(): array
    {
        return $this->providers;
    }
}
