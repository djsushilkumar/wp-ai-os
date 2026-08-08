<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Tools;

/**
 * Abstract Tool base class.
 */
abstract class AbstractTool implements ToolInterface
{
    public function validate(array $input): bool
    {
        return true;
    }

    public function authorize(): bool
    {
        return function_exists('current_user_can') ? current_user_can('manage_options') : true;
    }

    public function outputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'success' => ['type' => 'boolean'],
                'data' => ['type' => 'object'],
            ],
        ];
    }
}
