<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define WP constants for testing if not present
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/../');
}

if (!defined('WPAI_OS_VERSION')) {
    define('WPAI_OS_VERSION', '1.0.0-TEST');
}
