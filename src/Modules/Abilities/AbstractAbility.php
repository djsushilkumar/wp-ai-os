<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities;

use WPAIOS\Modules\Abilities\Contracts\AbilityInterface;

/**
 * Abstract Ability Base Class for all WP AI OS Abilities.
 */
abstract class AbstractAbility implements AbilityInterface
{
    protected string $version  = '1.0.0';
    protected string $category = 'System';
    /** @var string[] */
    protected array $permissions = [ 'manage_options' ];

    public function version(): string
    {
        return $this->version;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function validate(array $params): bool
    {
        return true;
    }

    public function authorize(): bool
    {
        if (function_exists('current_user_can')) {
            foreach ($this->permissions() as $cap) {
                if (! current_user_can($cap)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function metadata(): array
    {
        return [
            'id'          => $this->id(),
            'name'        => $this->name(),
            'description' => $this->description(),
            'category'    => $this->category(),
            'version'     => $this->version(),
            'permissions' => $this->permissions(),
        ];
    }
}
