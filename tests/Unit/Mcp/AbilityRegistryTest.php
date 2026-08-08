<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Mcp;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\Mcp\Abilities\AbilityRegistry;
use WPAIOS\Modules\Mcp\Abilities\AbstractAbility;

class MockAbility extends AbstractAbility
{
    public function id(): string
    {
        return 'test_ability';
    }

    public function name(): string
    {
        return 'Test Ability';
    }

    public function description(): string
    {
        return 'Ability for unit testing';
    }

    public function schema(): array
    {
        return ['type' => 'object'];
    }

    public function execute(array $params): mixed
    {
        return ['executed' => true];
    }
}

class AbilityRegistryTest extends TestCase
{
    public function testAbilityRegistry(): void
    {
        $registry = new AbilityRegistry();
        $ability = new MockAbility();

        $registry->register($ability);

        $this->assertTrue($registry->has('test_ability'));
        $this->assertSame($ability, $registry->get('test_ability'));
        $this->assertEquals(['executed' => true], $registry->get('test_ability')->execute([]));
    }
}
