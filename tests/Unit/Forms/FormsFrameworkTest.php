<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Forms;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\Forms\Adapters\ContactForm7Adapter;
use WPAIOS\Modules\Forms\Adapters\FallbackFormAdapter;
use WPAIOS\Modules\Forms\Adapters\FluentFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\FormidableFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\GravityFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\NinjaFormsAdapter;
use WPAIOS\Modules\Forms\Adapters\WPFormsAdapter;
use WPAIOS\Modules\Forms\Models\FormSubmissionModel;
use WPAIOS\Modules\Forms\Repositories\FormRepository;
use WPAIOS\Modules\Forms\Services\FormDiscovery;
use WPAIOS\Modules\Forms\Services\FormFactory;
use WPAIOS\Modules\Forms\Services\FormSubmissionManager;
use WPAIOS\Modules\Forms\Services\FormValidator;

class FormsFrameworkTest extends TestCase
{
    public function testProviderDiscovery(): void
    {
        $discovery = new FormDiscovery();
        $discovery->registerAdapter(new FluentFormsAdapter());
        $discovery->registerAdapter(new GravityFormsAdapter());
        $discovery->registerAdapter(new WPFormsAdapter());
        $discovery->registerAdapter(new ContactForm7Adapter());
        $discovery->registerAdapter(new NinjaFormsAdapter());
        $discovery->registerAdapter(new FormidableFormsAdapter());
        $discovery->registerAdapter(new FallbackFormAdapter());

        $report = $discovery->discoverProviders();

        $this->assertArrayHasKey('fluentform', $report);
        $this->assertArrayHasKey('gravityforms', $report);
        $this->assertArrayHasKey('wpforms', $report);
        $this->assertArrayHasKey('cf7', $report);
        $this->assertArrayHasKey('ninja_forms', $report);
        $this->assertArrayHasKey('formidable', $report);
        $this->assertArrayHasKey('wp_ai_os_native', $report);

        $this->assertEquals('active', $report['wp_ai_os_native']['status']);
    }

    public function testFormCreationAndRepository(): void
    {
        $discovery = new FormDiscovery();
        $fallback = new FallbackFormAdapter();
        $discovery->registerAdapter($fallback);

        $repo = new FormRepository($discovery);

        $form = FormFactory::createForm([
            'title' => 'Test Lead Form',
            'fields' => [
                ['id' => 'name', 'type' => 'text', 'label' => 'Name', 'required' => true],
                ['id' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => true],
            ],
        ]);

        $saved = $repo->save($form);
        $this->assertNotNull($saved->getId());

        $found = $repo->findById($saved->getId());
        $this->assertNotNull($found);
        $this->assertEquals('Test Lead Form', $found->getTitle());
    }

    public function testValidation(): void
    {
        $validator = new FormValidator();
        $form = FormFactory::createForm([
            'title' => 'Validation Form',
            'fields' => [
                ['id' => 'email', 'type' => 'email', 'label' => 'Email', 'required' => true],
            ],
        ]);

        $errorsEmpty = $validator->validate($form, []);
        $this->assertArrayHasKey('email', $errorsEmpty);

        $errorsInvalid = $validator->validate($form, ['email' => 'invalid-email']);
        $this->assertArrayHasKey('email', $errorsInvalid);

        $errorsValid = $validator->validate($form, ['email' => 'user@example.com']);
        $this->assertEmpty($errorsValid);
    }

    public function testPiiSanitizationForAudit(): void
    {
        $manager = new FormSubmissionManager();
        $submission = new FormSubmissionModel(
            'sub_123',
            'form_1',
            [
                'name' => 'John Doe',
                'password' => 'secret123',
                'credit_card' => '4111111111111111',
            ]
        );

        $audit = $manager->sanitizeSubmissionForAudit($submission);

        $this->assertEquals('John Doe', $audit['sanitized_data']['name']);
        $this->assertEquals('[REDACTED_PII]', $audit['sanitized_data']['password']);
        $this->assertEquals('[REDACTED_PII]', $audit['sanitized_data']['credit_card']);
    }
}
