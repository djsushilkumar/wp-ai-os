<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Abilities\Types\Developer;

use Exception;
use WPAIOS\Modules\Abilities\AbstractAbility;
use WPAIOS\Support\Filesystem;

/**
 * Developer Filesystem Ability for inspecting and managing plugin files.
 */
class FilesystemAbility extends AbstractAbility
{
    protected string $category   = 'Developer';
    protected array $permissions = [ 'manage_options' ];

    private Filesystem $fs;

    public function __construct(?Filesystem $fs = null)
    {
        $this->fs = $fs ?? new Filesystem();
    }

    public function id(): string
    {
        return 'wp_ai_os_dev_filesystem';
    }

    public function name(): string
    {
        return 'Developer Filesystem Manager';
    }

    public function description(): string
    {
        return 'Safely read, write, or list project directory files.';
    }

    public function schema(): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'action'  => [
                    'type' => 'string',
                    'enum' => [ 'read', 'write', 'exists', 'delete' ],
                ],
                'path'    => [ 'type' => 'string' ],
                'content' => [ 'type' => 'string' ],
            ],
            'required'   => [ 'action', 'path' ],
        ];
    }

    public function execute(array $params): mixed
    {
        $action = $params['action'];
        $path   = $params['path'];

        if ($action === 'read') {
            return [ 'content' => $this->fs->read($path) ];
        }

        if ($action === 'write') {
            $success = $this->fs->write($path, $params['content'] ?? '');
            return [ 'success' => $success ];
        }

        if ($action === 'exists') {
            return [ 'exists' => $this->fs->exists($path) ];
        }

        if ($action === 'delete') {
            return [ 'deleted' => $this->fs->delete($path) ];
        }

        throw new Exception('Invalid filesystem action.');
    }
}
