<?php

declare(strict_types=1);

namespace WPAIOS\Tests\Unit\Builders;

use PHPUnit\Framework\TestCase;
use WPAIOS\Modules\Builders\Adapters\BricksAdapter;
use WPAIOS\Modules\Builders\Adapters\DiviAdapter;
use WPAIOS\Modules\Builders\Adapters\ElementorAdapter;
use WPAIOS\Modules\Builders\Adapters\GutenbergAdapter;
use WPAIOS\Modules\Builders\Discovery\BuilderDiscovery;
use WPAIOS\Modules\Builders\Export\BuilderExporter;
use WPAIOS\Modules\Builders\Import\BuilderImporter;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Builders\Models\BuilderNode;
use WPAIOS\Modules\Builders\Registry\BuilderRegistry;
use WPAIOS\Modules\Builders\Themes\ThemeDiscovery;
use WPAIOS\Modules\Builders\Validators\BuilderValidator;

class BuildersFrameworkTest extends TestCase
{
    public function testBuilderRegistryAndDiscovery(): void
    {
        $registry = new BuilderRegistry();
        $registry->register(new ElementorAdapter());
        $registry->register(new GutenbergAdapter());
        $registry->register(new BricksAdapter());
        $registry->register(new DiviAdapter());

        $this->assertTrue($registry->has('elementor'));
        $this->assertTrue($registry->has('gutenberg'));
        $this->assertTrue($registry->has('bricks'));
        $this->assertTrue($registry->has('divi'));

        $discovery = new BuilderDiscovery($registry->all());
        $primary = $discovery->getPrimaryAdapter();

        $this->assertNotNull($primary);
        $this->assertEquals('gutenberg', $primary->getSlug());
    }

    public function testGutenbergBlockCompilation(): void
    {
        $adapter = new GutenbergAdapter();
        $doc = new BuilderDocument('doc_101', 'Homepage Blueprint', [
            new BuilderNode('node_1', 'group', ['align' => 'full']),
            new BuilderNode('node_2', 'heading', ['content' => 'Welcome']),
        ]);

        $compiled = $adapter->compileToNative($doc);
        $this->assertNotEmpty($compiled);
    }

    public function testUninstalledBuilderStubs(): void
    {
        $bricks = new BricksAdapter();
        $divi = new DiviAdapter();

        $this->assertFalse($bricks->isInstalled());
        $this->assertFalse($divi->isInstalled());

        $this->assertEquals('bricks', $bricks->getSlug());
        $this->assertEquals('divi', $divi->getSlug());
    }

    public function testDocumentValidationAndExportImport(): void
    {
        $doc = new BuilderDocument('doc_202', 'Landing Page', [
            new BuilderNode('n1', 'container', ['padding' => '20px']),
        ]);

        $validator = new BuilderValidator();
        $warnings = $validator->validate($doc);
        $this->assertEmpty($warnings);

        $exporter = new BuilderExporter();
        $exported = $exporter->export($doc);
        $this->assertEquals('1.0', $exported['version']);

        $importer = new BuilderImporter();
        $importedDoc = $importer->import($exported);
        $this->assertEquals('Landing Page', $importedDoc->getTitle());
    }

    public function testThemeDiscovery(): void
    {
        $themeDiscovery = new ThemeDiscovery();
        $adapter = $themeDiscovery->getActiveThemeAdapter();

        $this->assertNotNull($adapter->getThemeName());
        $this->assertIsArray($adapter->getGlobalStyles());
    }
}
