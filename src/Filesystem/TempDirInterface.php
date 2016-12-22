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
     * @return string
     */
    public function __toString();

    /**
     * Returns `$path`, prefixed with the concrete temporary directory’s
     * absolute path.
     *
     * @param string $path The relative path to be prefixed
     *
     * @return string The prefixed absolute path
     */
    public function prefixPath($path);

    /**
     * Creates a filesystem subtree (relative to the current temporary path)
     * from a data array with predefined format.
     *
     * @param array $structure The expected filesystem subtree, for example:
     *
     *                         [
     *                             'app' => [], // Will create an empty `app` directory
     *                             'var' => [
     *                                 'data'  => [], // Will create an empty `var/data` directory
     *                                 'cache' => [
     *                                     'view_cache.php' => '42', // Will create a `var/cache/view_cache.php` file with `42` as content
     *                                 ],
     *                             ],
     *                         ]
     * @param string $baseDir (optional) The subdirectory path (relative to the current temporary path) which the
     *                        expected subtree should be a child of
     */
    public function addStructure(array $structure, $baseDir = '');

    /**
     * Removes the concrete temporary directory from storage if it exists.
     */
    public function cleanup();
}
