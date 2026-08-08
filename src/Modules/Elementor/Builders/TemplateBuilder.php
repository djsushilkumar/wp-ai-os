<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Elementor\Builders;

/**
 * Template Builder for Elementor global templates (Header, Footer, Single, Archive, Popup).
 */
class TemplateBuilder
{
    /**
     * @param string $type 'header', 'footer', 'single', 'archive', 'popup', 'loop-item', 'page'
     * @param string $title
     * @param array<array<string, mixed>> $content Elementor AST elements.
     * @return array<string, mixed>
     */
    public function buildTemplate(string $type, string $title, array $content = []): array
    {
        return [
            'version' => '0.4',
            'title' => $title,
            'type' => $type,
            'content' => $content,
            'page_settings' => [
                'template' => 'elementor_' . $type,
            ],
        ];
    }

    /**
     * Build Header template.
     *
     * @param string $title
     * @param array<array<string, mixed>> $content
     * @return array<string, mixed>
     */
    public function buildHeader(string $title = 'Site Header', array $content = []): array
    {
        return $this->buildTemplate('header', $title, $content);
    }

    /**
     * Build Footer template.
     *
     * @param string $title
     * @param array<array<string, mixed>> $content
     * @return array<string, mixed>
     */
    public function buildFooter(string $title = 'Site Footer', array $content = []): array
    {
        return $this->buildTemplate('footer', $title, $content);
    }

    /**
     * Build Popup template.
     *
     * @param string $title
     * @param array<array<string, mixed>> $content
     * @return array<string, mixed>
     */
    public function buildPopup(string $title = 'Popup', array $content = []): array
    {
        return $this->buildTemplate('popup', $title, $content);
    }
}
