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
 * This parser will import config parameters from environment variables and/or
 * a `.env` file.
 *
 * The names of the variables to import will be taken from the examples set in
 * a `.env.example` file, whose path must be passed to the constructor. The
 * values for the resulting parameters will be taken from the following sources,
 * in descending order of priority:
 *
 * * preexisting environment variables
 * * `.env` file variables
 * * `null` if no matching value can be found
 *
 * This parser has pseudo-namespace support, as double underscores in an
 * environment variable name will be replaced with a dot in the resulting
 * parameter name, for example:
 *
 * * MAILER__SERVER_PORT ~> mailer.server_port
 * * SESSION__STORAGE__MAX_SIZE ~> session.storage.max_size
 *
 * Also, the parameter value will be automatically cast to an integer or a float
 * if the base value’s format is recognized as such (decimal format only), and
 * to a boolean if the base value is *true* or *false*.
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
     * @param Dotenv $dotenv          A fully initialized `Dotenv` instance
     * @param Loader $loader          A dummy `Loader` instance, whose `filePath` constructor parameter should be set to
     *                                `null`
     * @param string $exampleFilePath Path to the `.env.example` file which will define what environment variables will
     *                                be imported
     * @param array  $baseParams      A set of immutable base params, which will be part of the final config array and
     *                                whose value won’t be overwritten
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
     *
     * If `$this->exampleFilePath` doesn’t point to an existing file, nothing will be imported and `$this->baseParams`
     * will simply be returned.
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
                $params[$paramName] = $this->castValue($this->loader->getEnvironmentVariable($envVarName));
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

    /**
     * Casts the passed string value to its supposed expected type.
     *
     * @param string $value The raw string value
     *
     * @return mixed The cast value
     */
    private function castValue($value)
    {
        foreach ([
            // Integers
            '/^-?\d+$/' => function ($value) {
                return (int) $value;
            },
            // Floats
            '/^-?\d+\.\d+$/' => function ($value) {
                return (float) $value;
            },
            // Booleans
            '/^(?:true|false)$/' => function ($value) {
                return filter_var($value, \FILTER_VALIDATE_BOOLEAN);
            },
        ] as $format => $castFunction) {
            if (preg_match($format, $value)) {
                return $castFunction($value);
            }
        }

        return $value;
    }
}
