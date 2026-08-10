<?php
/**
 * WordPress Core Stubs for IDE Autocompletion and Static Analysis.
 */

if (function_exists('wp_initial_constants') || (defined('ABSPATH') && file_exists(ABSPATH . 'wp-admin/includes/admin.php'))) {
    return;
}

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

if (!function_exists('openssl_get_cipher_methods')) {
    function openssl_get_cipher_methods(bool $aliases = false): array { return ['aes-256-gcm']; }
}

if (!function_exists('openssl_encrypt')) {
    function openssl_encrypt(string $data, string $cipher_algo, string $passphrase, int $options = 0, string $iv = '', string &$tag = null, string $aad = '', int $tag_length = 16): string|false { return 'encrypted'; }
}

if (!function_exists('openssl_decrypt')) {
    function openssl_decrypt(string $data, string $cipher_algo, string $passphrase, int $options = 0, string $iv = '', string $tag = '', string $aad = ''): string|false { return 'decrypted'; }
}

if (!class_exists('Ninja_Forms')) {
    class Ninja_Forms {
        const VERSION = '3.0.0';
    }
}

if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request {
        public function get_param(string $key): mixed { return null; }
        public function get_params(): array { return []; }
        public function get_json_params(): array { return []; }
        public function get_header(string $header): ?string { return null; }
    }
}

if (!class_exists('WP_REST_Response')) {
    class WP_REST_Response {
        public function __construct(mixed $data = null, int $status = 200, array $headers = []) {}
    }
}

if (!function_exists('__')) {
    function __(string $text, string $domain = 'default'): string { return $text; }
}

if (!function_exists('current_time')) {
    function current_time(string $type, bool $gmt = false): string|int { return date('Y-m-d H:i:s'); }
}

if (!function_exists('get_attached_file')) {
    function get_attached_file(int $attachment_id, bool $unfiltered = false): string|false { return ''; }
}

if (!function_exists('wp_get_attachment_metadata')) {
    function wp_get_attachment_metadata(int $attachment_id = 0, bool $unfiltered = false): array|false { return []; }
}

if (!function_exists('wp_delete_attachment')) {
    function wp_delete_attachment(int $post_id, bool $force_delete = false): mixed { return true; }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags(string $string, bool $remove_breaks = false): string { return strip_tags($string); }
}

if (!function_exists('esc_url_raw')) {
    function esc_url_raw(string $url, array $protocols = null, string $_context = 'db'): string { return $url; }
}

if (!function_exists('wp_json_encode')) {
    function wp_json_encode(mixed $data, int $options = 0, int $depth = 512): string|false { return json_encode($data, $options, $depth); }
}

if (!function_exists('dbDelta')) {
    function dbDelta(string|array $queries = '', bool $execute = true): array { return []; }
}

if (!function_exists('wp_remote_retrieve_response_code')) {
    function wp_remote_retrieve_response_code(array|object $response): int|string { return 200; }
}

if (!function_exists('wp_remote_retrieve_body')) {
    function wp_remote_retrieve_body(array|object $response): string { return ''; }
}

if (!function_exists('wp_remote_retrieve_headers')) {
    function wp_remote_retrieve_headers(array|object $response): array { return []; }
}

if (!function_exists('esc_attr')) {
    function esc_attr(string $text): string { return $text; }
}

if (!function_exists('add_menu_page')) {
    function add_menu_page(string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = null, string $icon_url = '', int $position = null): string { return ''; }
}

if (!function_exists('wp_die')) {
    function wp_die(string $message = '', string $title = '', array $args = []): void {}
}

if (!function_exists('check_admin_referer')) {
    function check_admin_referer(string|int $action = -1, string $query_arg = '_wpnonce'): int|false { return 1; }
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce(string|int $action = -1): string { return 'nonce'; }
}

if (!function_exists('current_user_can')) {
    function current_user_can(string $capability, mixed ...$args): bool { return true; }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path(string $file): string { return dirname($file) . '/'; }
}

if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url(string $file): string { return 'http://localhost/wp-content/plugins/' . basename(dirname($file)) . '/'; }
}

if (!function_exists('esc_html__')) {
    function esc_html__(string $text, string $domain = 'default'): string { return $text; }
}

if (!function_exists('register_activation_hook')) {
    function register_activation_hook(string $file, callable $callback): void {}
}

if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook(string $file, callable $callback): void {}
}

if (!function_exists('add_action')) {
    function add_action(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): true { return true; }
}

if (!function_exists('add_filter')) {
    function add_filter(string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1): true { return true; }
}

if (!class_exists('WP_Error')) {
    class WP_Error {
        public function __construct(string|int $code = '', string $message = '', mixed $data = '') {}
        public function get_error_message(string|int $code = ''): string { return ''; }
        public function get_error_code(): string|int { return ''; }
        public function get_error_data(string|int $code = ''): mixed { return null; }
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field(string $str): string { return $str; }
}

if (!function_exists('sanitize_key')) {
    function sanitize_key(string $key): string { return $key; }
}

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name(string $filename): string { return $filename; }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post(string $content): string { return $content; }
}

if (!function_exists('wp_update_post')) {
    function wp_update_post(array|object $postarr = [], bool $wp_error = false, bool $fire_after_hooks = true): int|\WP_Error { return 0; }
}

if (!function_exists('wp_delete_post')) {
    function wp_delete_post(int $postid = 0, bool $force_delete = false): mixed { return true; }
}

if (!function_exists('get_post')) {
    function get_post(mixed $post = null, string $output = 'OBJECT', string $filter = 'raw'): mixed { return null; }
}

if (!function_exists('get_posts')) {
    function get_posts(array $args = []): array { return []; }
}

if (!function_exists('get_post_meta')) {
    function get_post_meta(int $post_id, string $key = '', bool $single = false): mixed { return null; }
}

if (!function_exists('update_post_meta')) {
    function update_post_meta(int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = ''): int|bool { return true; }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error(mixed $thing): bool { return false; }
}

if (!function_exists('wp_upload_dir')) {
    function wp_upload_dir(string $time = null, bool $create_dir = true, bool $refresh_cache = false): array { return []; }
}

if (!function_exists('wp_generate_attachment_metadata')) {
    function wp_generate_attachment_metadata(int $attachment_id, string $file): array { return []; }
}

if (!function_exists('wp_update_attachment_metadata')) {
    function wp_update_attachment_metadata(int $post_id, array $data): int|bool { return true; }
}
