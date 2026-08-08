<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Nonce utility wrapper for WordPress Nonce generation and verification.
 */
class Nonce
{
    /**
     * Create a security nonce for an action string.
     *
     * @param string $action
     * @return string
     */
    public function create(string $action = 'wp_ai_os_action'): string
    {
        if (function_exists('wp_create_nonce')) {
            return wp_create_nonce($action);
        }

        return md5($action . 'mock_nonce_salt');
    }

    /**
     * Verify a security nonce against an action string.
     *
     * @param string $nonce
     * @param string $action
     * @return bool
     */
    public function verify(string $nonce, string $action = 'wp_ai_os_action'): bool
    {
        if (function_exists('wp_verify_nonce')) {
            return false !== wp_verify_nonce($nonce, $action);
        }

        return !empty($nonce);
    }
}
