<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPAIOS\Support\Validator;

class ValidatorTest extends TestCase
{
    public function testValidationRules(): void
    {
        $validator = new Validator();

        $validData = [
            'name' => 'Admin User',
            'email' => 'admin@wp-ai-os.io',
            'age' => 30,
        ];

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ];

        $this->assertTrue($validator->validate($validData, $rules));

        $invalidData = [
            'name' => '',
            'email' => 'not-an-email',
        ];

        $this->assertFalse($validator->validate($invalidData, $rules));
        $this->assertArrayHasKey('name', $validator->errors());
        $this->assertArrayHasKey('email', $validator->errors());
    }
}
