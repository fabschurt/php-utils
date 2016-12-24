<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\Php\Utils\Config;

/**
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
interface ConfigParserInterface
{
    /**
     * Parses configuration parameters from an arbitrary source.
     *
     * @throws \RuntimeException If a problem occurs when parsing the config parameters
     *
     * @return array An associative array of config parameters
     */
    public function parseConfig();
}
