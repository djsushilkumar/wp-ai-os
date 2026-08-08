<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Import;

use WPAIOS\Modules\Builders\Contracts\BuilderImporterInterface;
use WPAIOS\Modules\Builders\Models\BuilderDocument;
use WPAIOS\Modules\Builders\Models\BuilderNode;

/**
 * Class BuilderImporter
 * Treats imported payloads as untrusted input with strict sanitization.
 */
class BuilderImporter implements BuilderImporterInterface
{
    public function import(array $data): BuilderDocument
    {
        $doc = $data['document'] ?? $data;
        $id = $doc['id'] ?? ('doc_' . uniqid());
        $title = function_exists('sanitize_text_field') ? sanitize_text_field($doc['title'] ?? 'Imported Document') : trim(strip_tags($doc['title'] ?? 'Imported Document'));

        $nodes = [];
        $rawNodes = $doc['nodes'] ?? [];
        foreach ($rawNodes as $n) {
            $type = function_exists('sanitize_key') ? sanitize_key($n['type'] ?? 'container') : preg_replace('/[^a-z0-9_\-]/i', '', $n['type'] ?? 'container');
            $nodes[] = new BuilderNode('node_' . uniqid(), $type, $n['settings'] ?? []);
        }

        return new BuilderDocument($id, $title, $nodes);
    }
}
