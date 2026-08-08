<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\AI;

use LogicException;
use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\AI\Security\KeyEncryptor;

class KeyEncryptorTest extends TestCase
{
    public function testEncryptionAndDecryption(): void
    {
        $encryptor = new KeyEncryptor('test_secret_key_123');
        $apiKey = 'sk-proj-1234567890abcdefghijklmnopqrstuvwxyz';

        $encrypted = $encryptor->encrypt($apiKey);
        $this->assertNotEquals($apiKey, $encrypted);

        $decrypted = $encryptor->decrypt($encrypted);
        $this->assertEquals($apiKey, $decrypted);
    }

    public function testRejectsWeakSecretSalt(): void
    {
        $this->expectException(LogicException::class);
        new KeyEncryptor('wp_ai_os_default_secret_salt_2026');
    }
}
