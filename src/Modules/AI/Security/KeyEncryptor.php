<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Security;

use LogicException;

/**
 * Key Encryptor for securing API keys at rest using OpenSSL AES-256-GCM.
 */
class KeyEncryptor
{
    private string $key;

    /**
     * @param string|null $secretKey
     * @throws LogicException
     */
    public function __construct(?string $secretKey = null)
    {
        $salt = $secretKey ?? (defined('AUTH_KEY') ? AUTH_KEY : null);

        if (empty($salt) || $salt === 'put your unique phrase here' || $salt === 'wp_ai_os_default_secret_salt_2026') {
            throw new LogicException('WP AI OS requires a valid, unique AUTH_KEY set in wp-config.php for API key encryption.');
        }

        $this->key = hash('sha256', $salt, true);
    }

    public function encrypt(string $plainText): string
    {
        if (empty($plainText)) {
            return '';
        }

        $iv = random_bytes(12);
        $tag = '';
        $cipherText = openssl_encrypt($plainText, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);

        return base64_encode($iv . $tag . $cipherText);
    }

    public function decrypt(string $encrypted): string
    {
        if (empty($encrypted)) {
            return '';
        }

        $data = base64_decode($encrypted, true);
        if (strlen($data) < 28) {
            return '';
        }

        $iv = substr($data, 0, 12);
        $tag = substr($data, 12, 16);
        $cipherText = substr($data, 28);

        $plain = openssl_decrypt($cipherText, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $iv, $tag);
        return false === $plain ? '' : $plain;
    }
}
