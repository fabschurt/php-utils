<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\Php\Utils\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

/**
 * This implementation represents a temporary directory stored on a regular,
 * local, UNIX-compatible filesystem.
 *
 * It uses `symfony/filesystem` as its access layer to increase compatibility,
 * and the PHP `sys_get_temp_dir()` core function for path generation.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
final class TempDir implements TempDirInterface
{
    /**
     * @var string The memoized absolute path to the temporary directory
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string The prefix that will be applied to the temporary directory's name
     */
    private $dirNamePrefix;

    /**
     * @param Filesystem $filesystem
     * @param string     $dirNamePrefix The prefix that will be applied to the temporary directory's name
     */
    public function __construct(Filesystem $filesystem, string $dirNamePrefix = 'php-utils.')
    {
        $this->filesystem    = $filesystem;
        $this->dirNamePrefix = $dirNamePrefix;
    }

    /**
     * {@inheritDoc}
     *
     * It will lazy-create the concrete directory on the fly if it doesn't
     * exist, and store its path into the `$path` property, or just return the
     * path if it already exists.
     */
    public function __toString(): string
    {
        if (is_null($this->path)) {
            $path = sprintf('%s/%s', sys_get_temp_dir(), uniqid($this->dirNamePrefix));
            $this->filesystem->mkdir($path);
            $this->path = $path;
        }

        return $this->path;
    }

    /**
     * {@inheritDoc}
     *
     * Leading slash(es) will be trimmed from `$path`.
     */
    public function prefixPath(string $path): string
    {
        $path = ltrim($path, '/');

        return "{$this}/{$path}";
    }

    /**
     * {@inheritDoc}
     */
    public function cleanup()
    {
        if (!is_null($this->path) && $this->path !== '/') {
            $this->filesystem->remove($this->path);
            $this->path = null;
        }
    }
}
