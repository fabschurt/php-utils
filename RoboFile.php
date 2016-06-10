<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/vendor/autoload.php';

use Robo\Tasks;

/**
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
class RoboFile extends Tasks
{
    public function __construct()
    {
        $this->stopOnFail();
    }

    /**
     * Runs tests
     */
    public function test(string $testFile = '')
    {
        $this->_exec("./vendor/bin/peridot --force-colors -b {$testFile}");
    }
}
