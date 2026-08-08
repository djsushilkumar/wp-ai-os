<?php

declare(strict_types=1);

namespace WPAIOS\Support;

use Exception;

/**
 * Safe Filesystem operations utility class.
 */
class Filesystem
{
    /**
     * Read contents of a file cleanly.
     *
     * @param string $path
     * @return string
     * @throws Exception
     */
    public function read(string $path): string
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new Exception(sprintf('File [%s] does not exist or is not readable.', $path));
        }

        $contents = file_get_contents($path);
        if (false === $contents) {
            throw new Exception(sprintf('Failed to read file [%s].', $path));
        }

        return $contents;
    }

    /**
     * Write contents to a file atomically.
     *
     * @param string $path
     * @param string $contents
     * @return bool
     */
    public function write(string $path, string $contents): bool
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $tempPath = $path . '.' . uniqid('tmp_', true);
        if (false === file_put_contents($tempPath, $contents, LOCK_EX)) {
            return false;
        }

        return rename($tempPath, $path);
    }

    /**
     * Check if a path exists.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Delete a file safely.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (file_exists($path)) {
            return unlink($path);
        }

        return true;
    }
}
