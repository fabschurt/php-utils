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

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Dotenv\Loader;
use function Stringy\create as s;

/**
 * This parser will import config parameters from environment variables.
 *
 * Variables described in the `.env.example` file will be imported as environment
 * variables and then as application parameters, with values being taken from
 * preexisting environment variables first, and then optionally from a `.env`
 * file (without overwriting existing non-null values), or set to null if there’s
 * no matching value to be found.
 *
 * It has pseudo-namespace support, as double underscores in an environment
 * variable name will be replaced with a dot in the resulting parameter name,
 * for example:
 *
 * * MAILER__SERVER_PORT ~> mailer.server_port
 * * SESSION__STORAGE__MAX_SIZE ~> session.storage.max_size
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
final class EnvVarConfigParser implements ConfigParserInterface
{
    /**
     * @var Dotenv
     */
    private $dotenv;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var string
     */
    private $exampleFilePath;

    /**
     * @var array
     */
    private $baseParams;

    /**
     * @param Dotenv $dotenv
     * @param Loader $loader
     * @param string $exampleFilePath
     * @param array  $baseParams      (optional) Default: []
     */
    public function __construct(Dotenv $dotenv, Loader $loader, $exampleFilePath, array $baseParams = [])
    {
        $this->dotenv          = $dotenv;
        $this->loader          = $loader;
        $this->exampleFilePath = $exampleFilePath;
        $this->baseParams      = $baseParams;
    }

    /**
     * {@inheritDoc}
     */
    public function parseConfig()
    {
        $params = $this->baseParams;
        if (!is_file($this->exampleFilePath)) {
            return $params;
        }
        try {
            $this->dotenv->load();
        } catch (InvalidPathException $e) {
        }
        foreach (array_keys(parse_ini_file($this->exampleFilePath)) as $envVarName) {
            try {
                $paramName = $this->convertEnvVarNameToParamName($envVarName);
            } catch (\InvalidArgumentException $e) {
                throw new \RuntimeException($e->getMessage());
            }
            if (!isset($params[$paramName]) || $params[$paramName] === null) {
                $params[$paramName] = $this->loader->getEnvironmentVariable($envVarName);
            }
        }

        return $params;
    }

    /**
     * This will apply the following transformations to `$envVarName`:
     *
     * * lower-cased
     * * double underscores replaced with dots
     *
     * @param string $envVarName
     *
     * @throws \InvalidArgumentException If the variable name is invalid
     *
     * @return string
     */
    private function convertEnvVarNameToParamName($envVarName)
    {
        if (!preg_match('/^[A-Z]+(?:_{1,2}[A-Z]+)*$/', $envVarName)) {
            throw new \InvalidArgumentException("`{$envVarName}` is not a valid environment variable name.");
        }

        return (string) s($envVarName)
            ->toLowerCase()
            ->replace('__', '.')
        ;
    }
}
