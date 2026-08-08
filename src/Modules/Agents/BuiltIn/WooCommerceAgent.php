<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\BuiltIn;

use WPAIOS\Modules\Agents\AbstractAgent;
use WPAIOS\Modules\Agents\Profiles\AgentProfile;

class WooCommerceAgent extends AbstractAgent
{
    public function __construct()
    {
        parent::__construct(new AgentProfile(
            'woocommerce',
            'WooCommerce Agent',
            'Manages products, inventory, and store configuration via WooCommerce abilities.',
            '1.0.0',
            'ecommerce',
            'HIGH',
            ['wp_ai_os_woo_products', 'wp_ai_os_woo_inventory']
        ));
    }
}
