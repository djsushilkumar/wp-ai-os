<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Mcp\Services;

/**
 * Version Compatibility Manager verifying WordPress, PHP, and MCP Plugin versions.
 */
class VersionCompatibility
{
    public const REQUIRED_PHP = '8.2.0';
    public const REQUIRED_WP = '6.4.0';

    /**
     * Check runtime platform version compatibility.
     *
     * @return array{compatible: bool, php: array{current: string, compatible: bool}, wp: array{current: string, compatible: bool}, mcp_plugin: array{detected: bool, version: string}}
     */
    public function checkCompatibility(): array
    {
        $phpCurrent = PHP_VERSION;
        $phpCompatible = version_compare($phpCurrent, self::REQUIRED_PHP, '>=');

        $wpCurrent = function_exists('get_bloginfo') ? get_bloginfo('version') : '6.4.0';
        $wpCompatible = version_compare($wpCurrent, self::REQUIRED_WP, '>=');

        $mcpDetected = false;
        $mcpVersion = '0.0.0';

        if (function_exists('is_plugin_active') || defined('WP_AGENT_ABILITIES_VERSION') || class_exists('WP_Agent_Abilities')) {
            $mcpDetected = true;
            $mcpVersion = defined('WP_AGENT_ABILITIES_VERSION') ? (string) WP_AGENT_ABILITIES_VERSION : '1.0.0';
        }

        return [
            'compatible' => $phpCompatible && $wpCompatible,
            'php' => [
                'current' => $phpCurrent,
                'compatible' => $phpCompatible,
            ],
            'wp' => [
                'current' => $wpCurrent,
                'compatible' => $wpCompatible,
            ],
            'mcp_plugin' => [
                'detected' => $mcpDetected,
                'version' => $mcpVersion,
            ],
        ];
    }
}
