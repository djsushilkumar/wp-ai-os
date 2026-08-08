<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderComponent
 */
class BuilderComponent extends BuilderNode
{
    public function __construct(string $id, string $type, array $settings = [])
    {
        parent::__construct($id, $type, $settings, []);
    }
}
