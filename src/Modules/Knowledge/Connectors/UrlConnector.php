<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Knowledge\Connectors;

use InvalidArgumentException;
use WPAIOS\Modules\Knowledge\Contracts\ConnectorInterface;
use WPAIOS\Modules\Knowledge\Contracts\KnowledgeSourceInterface;

/**
 * Class UrlConnector
 * External URL Ingestion with strict SSRF protection (blocking localhost and private IP subnets).
 */
class UrlConnector implements KnowledgeSourceInterface, ConnectorInterface
{
    private bool $connected = false;

    public function getId(): string
    {
        return 'url_connector';
    }

    public function getName(): string
    {
        return 'External URL Connector';
    }

    public function getType(): string
    {
        return 'external_url';
    }

    public function getCapabilities(): array
    {
        return ['http', 'https'];
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

    public function validateUrl(string $url): string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? '';

        if (empty($host)) {
            throw new InvalidArgumentException('Invalid URL structure.');
        }

        // SSRF Blocklist check
        $hostLower = strtolower($host);
        if ('localhost' === $hostLower || '127.0.0.1' === $hostLower || '::1' === $hostLower) {
            throw new InvalidArgumentException('SSRF Violation: Localhost access denied.');
        }

        $ip = gethostbyname($host);
        if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            throw new InvalidArgumentException('SSRF Violation: Private network IP access denied.');
        }

        return $url;
    }
}
