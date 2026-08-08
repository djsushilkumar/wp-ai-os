<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Security;

/**
 * Key Storage managing encrypted API keys in WordPress database options.
 */
class KeyStorage
{
    public function __construct(private KeyEncryptor $encryptor)
    {
    }

    /**
     * Store provider API key securely.
     *
     * @param string $provider
     * @param string $apiKey
     * @return void
     */
    public function saveKey(string $provider, string $apiKey): void
    {
        $encrypted = $this->encryptor->encrypt($apiKey);
        if (function_exists('update_option')) {
            update_option('wp_ai_os_api_key_' . $provider, $encrypted);
        }
    }

    /**
     * Retrieve decrypted provider API key.
     *
     * @param string $provider
     * @return string
     */
    public function getKey(string $provider): string
    {
        if (function_exists('get_option')) {
            $encrypted = get_option('wp_ai_os_api_key_' . $provider, '');
            if (is_string($encrypted) && !empty($encrypted)) {
                return $this->encryptor->decrypt($encrypted);
            }
        }

        return '';
    }
}
