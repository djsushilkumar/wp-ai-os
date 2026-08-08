<?php
/**
 * Plugin Name: WP AI OS (WordPress AI Operating System)
 * Plugin URI:  https://github.com/your-org/wp-ai-os
 * Description: Enterprise-grade AI Operating System for WordPress that extends WordPress Agent Abilities for MCP.
 * Version:     1.0.0
 * Author:      Lead WordPress AI Platform Engineer
 * Author URI:  https://wp-ai-os.io
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-ai-os
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 8.2
 */

declare(strict_types=1);

use WPAIOS\Services\PluginActivator;
use WPAIOS\Services\PluginDeactivator;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// 1. Minimum PHP Version Check
if (PHP_VERSION_ID < 80200) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p>' .
            esc_html__('WP AI OS requires PHP 8.2 or higher. Please upgrade your server environment.', 'wp-ai-os') .
            '</p></div>';
    });
    return;
}

// 2. Define Core Directory and Version Constants
define('WPAI_OS_VERSION', '1.0.0');
define('WPAI_OS_FILE', __FILE__);
define('WPAI_OS_PATH', plugin_dir_path(__FILE__));
define('WPAI_OS_URL', plugin_dir_url(__FILE__));

// 3. Load Autoloader
$autoloader = WPAI_OS_PATH . 'vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
} else {
    spl_autoload_register(static function (string $class): void {
        $prefix = 'WPAIOS\\';
        $baseDir = WPAI_OS_PATH . 'src/';
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    });
}

// 4. Register Activation & Deactivation Hooks
register_activation_hook(__FILE__, static function (): void {
    $activator = new PluginActivator();
    $activator->activate();
});

register_deactivation_hook(__FILE__, static function (): void {
    $deactivator = new PluginDeactivator();
    $deactivator->deactivate();
});

// 5. Initialize Application Container & Kernel
add_action('plugins_loaded', static function (): void {
    $bootstrapFile = WPAI_OS_PATH . 'bootstrap/app.php';
    if (file_exists($bootstrapFile)) {
        require_once $bootstrapFile;
    }
}, 10);
