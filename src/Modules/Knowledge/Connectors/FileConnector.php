<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Connectors;

use WPAIOS\Modules\Knowledge\Contracts\ConnectorInterface;
use WPAIOS\Modules\Knowledge\Contracts\KnowledgeSourceInterface;

/**
 * Class FileConnector
 * Ingests local uploaded documents (text, PDF, CSV, JSON) safely.
 */
class FileConnector implements KnowledgeSourceInterface, ConnectorInterface
{
    private bool $connected = false;

    public function getId(): string
    {
        return 'file_connector';
    }

    public function getName(): string
    {
        return 'Document File Connector';
    }

    public function getType(): string
    {
        return 'file';
    }

    public function getCapabilities(): array
    {
        return ['txt', 'pdf', 'csv', 'json'];
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
        return [];
    }
}
