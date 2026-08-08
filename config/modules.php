<?php

declare(strict_types=1);

return [
    'enabled' => [
        \WPAIOS\Modules\Mcp\McpModule::class,
        \WPAIOS\Modules\Abilities\AbilitiesModule::class,
        \WPAIOS\Modules\AI\AiModule::class,
        \WPAIOS\Modules\Elementor\ElementorModule::class,
        \WPAIOS\Modules\Automation\AutomationModule::class,
        \WPAIOS\Modules\Integration\IntegrationModule::class,
        \WPAIOS\Modules\WooCommerce\WooCommerceModule::class,
        \WPAIOS\Modules\SEO\SEOModule::class,
        \WPAIOS\Modules\Media\MediaModule::class,
    ],
];
