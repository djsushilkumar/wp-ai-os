<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Agents\Abilities;

use WPAIOS\Modules\Agents\AgentsManager;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

/**
 * Ability: agents/get
 */
class AgentsGetAbility extends AbstractAbility
{
    public function __construct(private AgentsManager $manager)
    {
    }

    public function getName(): string
    {
        return 'wp_ai_os_agents_get';
    }

    public function getDescription(): string
    {
        return 'Get profile details, risk level, and allowed abilities for a specific agent.';
    }

    public function getInputSchema(): array
    {
        return [
            'type'       => 'object',
            'required'   => [ 'agent_id' ],
            'properties' => [
                'agent_id' => [
                    'type'        => 'string',
                    'description' => 'Agent ID',
                ],
            ],
        ];
    }

    public function execute(array $params): array
    {
        $id    = $params['agent_id'];
        $agent = $this->manager->getRegistry()->get($id);

        if (! $agent) {
            return [
                'success' => false,
                'error'   => 'Agent not found',
            ];
        }

        return [
            'success' => true,
            'agent'   => [
                'id'          => $agent->getId(),
                'name'        => $agent->getName(),
                'description' => $agent->getDescription(),
                'role'        => $agent->getRole(),
                'risk_level'  => $agent->getRiskLevel(),
            ],
        ];
    }
}
