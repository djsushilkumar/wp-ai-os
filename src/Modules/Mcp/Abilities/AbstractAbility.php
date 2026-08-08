<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Abilities;

/**
 * Abstract Ability Base Class providing standard validation, permission checking, and health checks.
 */
abstract class AbstractAbility implements AbilityInterface
{
    protected string $version = '1.0.0';
    /** @var string[] */
    protected array $permissions = ['manage_options'];

    public function id(): string
    {
        return $this->getName();
    }

    public function name(): string
    {
        return $this->getName();
    }

    public function description(): string
    {
        return $this->getDescription();
    }

    public function schema(): array
    {
        return $this->getInputSchema();
    }

    public function getName(): string
    {
        return '';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getInputSchema(): array
    {
        return [];
    }

    public function version(): string
    {
        return $this->version;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function validate(array $params): bool
    {
        // Default schema validation check
        return true;
    }

    public function authorize(): bool
    {
        if (function_exists('current_user_can')) {
            foreach ($this->permissions() as $cap) {
                if (!current_user_can($cap)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function metadata(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'version' => $this->version(),
            'permissions' => $this->permissions(),
        ];
    }

    public function health(): array
    {
        return [
            'healthy' => true,
            'message' => 'Ability operational',
        ];
    }
}
