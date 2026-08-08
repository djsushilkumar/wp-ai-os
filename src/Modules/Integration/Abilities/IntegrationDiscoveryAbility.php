<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Integration\Abilities;

use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Modules\Integration\Discovery\PluginDiscoveryManager;

/**
 * Integration Discovery Ability — scans active third-party plugin integrations for MCP agents.
 */
class IntegrationDiscoveryAbility extends AbstractAbility
{
    protected string $category = 'System';
    protected array $permissions = ['manage_options'];

    public function __construct(private PluginDiscoveryManager $discoveryManager)
    {
    }

    public function id(): string
    {
        return 'wp_ai_os_discover_integrations';
    }

    public function name(): string
    {
        return 'Integration Discovery Scanner';
    }

    public function description(): string
    {
        return 'Scans the WordPress environment to identify active plugin adapters (Elementor, WooCommerce, Gutenberg, RankMath, Yoast, ACF, Fluent Forms).';
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): mixed
    {
        return $this->discoveryManager->discover();
    }
}
