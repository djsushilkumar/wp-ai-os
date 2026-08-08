<?php

declare(strict_types=1);

namespace WPAIOS\Services;

/**
 * Version Manager Service tracking installed version, target version, and schema states.
 */
class VersionManager
{
    /**
     * @param string $currentVersion Current plugin codebase version string (e.g., '1.0.0').
     */
    public function __construct(private string $currentVersion)
    {
    }

    /**
     * Get current codebase version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->currentVersion;
    }

    /**
     * Get stored database version option.
     *
     * @return string
     */
    public function getInstalledVersion(): string
    {
        if (function_exists('get_option')) {
            $val = get_option('wp_ai_os_version', '0.0.0');
            return is_string($val) ? $val : '0.0.0';
        }

        return '0.0.0';
    }

    /**
     * Update stored database version option.
     *
     * @param string $version
     * @return void
     */
    public function setInstalledVersion(string $version): void
    {
        if (function_exists('update_option')) {
            update_option('wp_ai_os_version', $version);
        }
    }

    /**
     * Check if a database schema upgrade is required.
     *
     * @return bool
     */
    public function needsUpgrade(): bool
    {
        return version_compare($this->getInstalledVersion(), $this->currentVersion, '<');
    }
}
