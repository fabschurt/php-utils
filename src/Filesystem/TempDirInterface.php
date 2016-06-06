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

/**
 * Implementations of this class are expected to represent a temporary directory
 * on any kind of storage device.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
interface TempDirInterface
{
    /**
     * Returns the absolute path to the concrete temporary directory.
     *
     * @return string The absolute path to the concrete temporary directory
     */
    public function __toString(): string;

    /**
     * Returns `$path`, prefixed with the concrete temporary directory's
     * absolute path.
     *
     * @param string $path The relative path to prefix
     *
     * @return string The prefixed absolute path
     */
    public function prefixPath(string $path): string;

    /**
     * Removes the concrete temporary directory from storage if it exists.
     */
    public function cleanup();
}
