<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Builders\Services;

use WPAIOS\Modules\Builders\Models\BuilderDocument;

/**
 * Class BuilderPreviewService
 */
class BuilderPreviewService
{
    public function generatePreviewHtml(BuilderDocument $document): string
    {
        $html = sprintf('<!-- Builder Document Preview: %s -->', htmlspecialchars($document->getTitle()));
        $html .= '<div class="wp-ai-os-builder-preview">';

        foreach ($document->getNodes() as $node) {
            $html .= sprintf('<div class="builder-node node-%s">', htmlspecialchars($node->getType()));
            $html .= sprintf('<h3>%s</h3>', htmlspecialchars($node->getType()));
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }
}
