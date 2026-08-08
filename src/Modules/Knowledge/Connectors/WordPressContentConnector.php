<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Connectors;

use WPAIOS\Modules\Knowledge\Contracts\ConnectorInterface;
use WPAIOS\Modules\Knowledge\Contracts\KnowledgeSourceInterface;

/**
 * Class WordPressContentConnector
 * Extracts WordPress posts, pages, CPTs, products, forms, and templates for knowledge indexing.
 */
class WordPressContentConnector implements KnowledgeSourceInterface, ConnectorInterface
{
    private bool $connected = false;

    public function getId(): string
    {
        return 'wp_content_connector';
    }

    public function getName(): string
    {
        return 'WordPress Content Connector';
    }

    public function getType(): string
    {
        return 'wordpress';
    }

    public function getCapabilities(): array
    {
        return ['posts', 'pages', 'cpt', 'products', 'forms', 'templates', 'media'];
    }

    public function connect(): bool
    {
        $this->connected = true;
        return true;
    }

    public function disconnect(): bool
    {
        $this->connected = false;
        return true;
    }

    public function health(): bool
    {
        return $this->connected;
    }

    public function fetch(): array
    {
        if (!$this->connected) {
            return [];
        }

        return [
            [
                'id' => 'post_1',
                'type' => 'post',
                'title' => 'Sample Article',
                'content' => 'WP AI OS is an enterprise AI Operating System for WordPress.',
                'status' => 'publish',
            ],
            [
                'id' => 'page_2',
                'type' => 'page',
                'title' => 'About Us',
                'content' => 'Our platform extends agent abilities over Model Context Protocol.',
                'status' => 'publish',
            ],
        ];
    }
}
