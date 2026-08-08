<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Elementor;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\Elementor\Builders\ContainerBuilder;
use WPAIOS\Modules\Elementor\Builders\PageBuilder;
use WPAIOS\Modules\Elementor\Builders\SectionBuilder;
use WPAIOS\Modules\Elementor\Style\StyleEngine;
use WPAIOS\Modules\Elementor\Validation\ElementorValidator;

class ElementorBuilderTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private SectionBuilder $sectionBuilder;
    private StyleEngine $styleEngine;
    private PageBuilder $pageBuilder;
    private ElementorValidator $validator;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->sectionBuilder = new SectionBuilder();
        $this->styleEngine = new StyleEngine();
        $this->pageBuilder = new PageBuilder(
            $this->containerBuilder,
            $this->sectionBuilder,
            $this->styleEngine
        );
        $this->validator = new ElementorValidator();
    }

    public function testContainerBuilderProducesValidNode(): void
    {
        $node = $this->containerBuilder->createContainer([], 'column', 'boxed');

        $this->assertEquals('container', $node['elType']);
        $this->assertNotEmpty($node['id']);
        $this->assertIsArray($node['elements']);
        $this->assertEquals('column', $node['settings']['flex_direction']);
    }

    public function testSectionBuilderProducesValidNode(): void
    {
        $column = $this->sectionBuilder->createColumn([], 5);
        $section = $this->sectionBuilder->createSection([$column]);

        $this->assertEquals('section', $section['elType']);
        $this->assertCount(1, $section['elements']);
        $this->assertEquals('column', $section['elements'][0]['elType']);
    }

    public function testPageBuilderFromDefinition(): void
    {
        $definition = [
            'title' => 'Test Page',
            'sections' => [
                [
                    'type' => 'container',
                    'flex_direction' => 'column',
                    'children' => [
                        [
                            'type' => 'widget',
                            'widget_type' => 'heading',
                            'settings' => ['title' => 'Hello World'],
                        ],
                    ],
                ],
            ],
        ];

        $ast = $this->pageBuilder->buildFromDefinition($definition);

        $this->assertIsArray($ast);
        $this->assertArrayHasKey('content', $ast);
        $this->assertNotEmpty($ast['content']);

        $container = $ast['content'][0];
        $this->assertEquals('container', $container['elType']);
        $this->assertCount(1, $container['elements']);
        $this->assertEquals('heading', $container['elements'][0]['widgetType']);
    }

    public function testValidatorAcceptsValidElement(): void
    {
        $element = [
            'id' => 'abc1234',
            'elType' => 'widget',
            'widgetType' => 'heading',
            'settings' => ['title' => 'Hello'],
            'elements' => [],
        ];

        $errors = $this->validator->validateElement($element);
        $this->assertEmpty($errors);
    }

    public function testValidatorRejectsMissingId(): void
    {
        $element = [
            'elType' => 'widget',
            'widgetType' => 'heading',
            'settings' => [],
            'elements' => [],
        ];

        $errors = $this->validator->validateElement($element);
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('id', $errors[0]);
    }

    public function testStyleEngineGeneratesSpacing(): void
    {
        $spacing = $this->styleEngine->spacing(20, 40, 20, 40, 'px', 'padding');
        $this->assertEquals('20', $spacing['padding']['top']);
        $this->assertEquals('40', $spacing['padding']['right']);
    }

    public function testStyleEngineGeneratesGradient(): void
    {
        $gradient = $this->styleEngine->gradientBackground('#000000', '#ffffff', 'linear', 135);
        $this->assertEquals('gradient', $gradient['background_background']);
        $this->assertEquals('#000000', $gradient['background_color']);
        $this->assertEquals(135, $gradient['background_gradient_angle']['size']);
    }
}
