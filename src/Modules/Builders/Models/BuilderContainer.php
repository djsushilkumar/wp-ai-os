<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Models;

/**
 * Class BuilderContainer
 */
class BuilderContainer extends BuilderNode
{
    public function __construct(string $id, array $settings = [], array $children = [])
    {
        parent::__construct($id, 'container', $settings, $children);
    }
}
