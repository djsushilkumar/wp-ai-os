<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Adapters;

use WPAIOS\Modules\Builders\Models\BuilderCapabilitiesModel;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Builders\Models\BuilderNode;

/**
 * Class GutenbergAdapter
 * Adapter for WordPress Block Editor (Gutenberg) using verified core block APIs.
 */
class GutenbergAdapter extends AbstractBuilderAdapter
{
    public function getSlug(): string
    {
        return 'gutenberg';
    }

    public function getName(): string
    {
        return 'Gutenberg Block Editor';
    }

    public function isInstalled(): bool
    {
        return function_exists('parse_blocks') || function_exists('register_block_type');
    }

    public function isActive(): bool
    {
        return true; // Built-in to WP core
    }

    public function getVersion(): ?string
    {
        return function_exists('get_bloginfo') ? get_bloginfo('version') : '6.5.0';
    }

    public function getCapabilities(): BuilderCapabilitiesModel
    {
        return new BuilderCapabilitiesModel(
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            false,
            true,
            true,
            true
        );
    }

    public function compileToNative(BuilderDocument $document): mixed
    {
        $blocks = [];
        foreach ($document->getNodes() as $node) {
            $blocks[] = [
                'blockName' => 'core/' . ($node->getType() === 'container' ? 'group' : $node->getType()),
                'attrs' => $node->getSettings(),
                'innerBlocks' => [],
                'innerHTML' => '',
                'innerContent' => [],
            ];
        }

        if (function_exists('serialize_blocks')) {
            return serialize_blocks($blocks);
        }

        return json_encode($blocks);
    }

    public function parseFromNative(mixed $nativeData): BuilderDocument
    {
        if (is_string($nativeData) && function_exists('parse_blocks')) {
            $parsedBlocks = parse_blocks($nativeData);
            $nodes = [];
            foreach ($parsedBlocks as $b) {
                if (empty($b['blockName'])) {
                    continue;
                }
                $type = str_replace('core/', '', $b['blockName']);
                $nodes[] = new BuilderNode('block_' . uniqid(), $type, $b['attrs'] ?? []);
            }
            return new BuilderDocument('doc_' . uniqid(), 'Gutenberg Document', $nodes);
        }

        return parent::parseFromNative($nativeData);
    }
}
