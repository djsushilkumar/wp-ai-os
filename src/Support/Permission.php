<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Permission utility wrapper for WordPress User Role & Capability checks.
 */
class Permission
{
    /**
     * Check if current user has a specific capability.
     *
     * @param string $capability E.g., 'manage_options', 'edit_posts'.
     * @param mixed ...$args
     * @return bool
     */
    public function check(string $capability, mixed ...$args): bool
    {
        if (function_exists('current_user_can')) {
            return current_user_can($capability, ...$args);
        }

        return false;
    }

    /**
     * Ensure current user has capability or throw exception.
     *
     * @param string $capability
     * @throws \Exception
     */
    public function authorize(string $capability): void
    {
        if (!$this->check($capability)) {
            throw new \Exception(sprintf('Permission Denied: User lacks required capability [%s].', $capability));
        }
    }
}
