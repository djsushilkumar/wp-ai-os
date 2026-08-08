<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Widgets;

use Exception;

/**
 * Widget Registry managing supported Elementor widgets.
 */
class WidgetRegistry
{
    /**
     * @var array<string, AbstractWidgetBuilder>
     */
    private array $widgets = [];

    public function register(AbstractWidgetBuilder $builder): void
    {
        $this->widgets[$builder->widgetType()] = $builder;
    }

    public function get(string $type): AbstractWidgetBuilder
    {
        if (!isset($this->widgets[$type])) {
            throw new Exception(sprintf('Widget type [%s] is not registered.', $type));
        }

        return $this->widgets[$type];
    }

    public function has(string $type): bool
    {
        return isset($this->widgets[$type]);
    }

    /**
     * @return array<string, AbstractWidgetBuilder>
     */
    public function all(): array
    {
        return $this->widgets;
    }
}
