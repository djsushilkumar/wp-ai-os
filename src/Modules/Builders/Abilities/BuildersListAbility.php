<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Abilities;

use WPAIOS\Modules\Builders\BuildersManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: builders/list
 */
class BuildersListAbility extends AbstractAbility
{
    public function __construct(private BuildersManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_builders_list';
    }

    public function getDescription(): string
    {
        return 'List all registered page builder engines and their current status.';
    }

    public function getInputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [],
        ];
    }

    public function execute(array $params): array
    {
        $report = $this->manager->getRegistry()->detect();
        return [
            'success' => true,
            'count' => count($report),
            'builders' => $report,
        ];
    }
}
