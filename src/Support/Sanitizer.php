<?php

declare(strict_types=1);

namespace WPAIOS\Support;

/**
 * Sanitizer utility for clean input escaping and sanitization.
 */
class Sanitizer
{
    /**
     * Sanitize plain text string.
     *
     * @param string $value
     * @return string
     */
    public function text(string $value): string
    {
        if (function_exists('sanitize_text_field')) {
            return sanitize_text_field($value);
        }

        return trim(htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Sanitize email address.
     *
     * @param string $email
     * @return string
     */
    public function email(string $email): string
    {
        if (function_exists('sanitize_email')) {
            return sanitize_email($email);
        }

        return (string) filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize key/slug string.
     *
     * @param string $key
     * @return string
     */
    public function key(string $key): string
    {
        if (function_exists('sanitize_key')) {
            return sanitize_key($key);
        }

        return strtolower(preg_replace('/[^a-z0-9_\-]/i', '', $key));
    }

    /**
     * Sanitize HTML content via wp_kses_post or strip_tags.
     *
     * @param string $html
     * @return string
     */
    public function html(string $html): string
    {
        if (function_exists('wp_kses_post')) {
            return wp_kses_post($html);
        }

        return strip_tags($html, '<p><a><br><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6>');
    }

    /**
     * Sanitize integer.
     *
     * @param mixed $value
     * @return int
     */
    public function int(mixed $value): int
    {
        return (int) $value;
    }
}
