<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dotenv\Dotenv;
use Dotenv\Loader;
use FabSchurt\Php\Utils\Config\EnvVarConfigParser;
use org\bovigo\vfs\vfsStream;

describe(EnvVarConfigParser::class, function () {
    beforeEach(function () {
        $this->subjectFactory = function ($createExampleFile = true, $addInvalidEntry = false, $baseParams = []) {
            $dotenvExampleFile = <<<'DOTENV'
VAR_IN_ENVIRONMENT_AND_DOTENV_FILE=example_value
VAR_IN_DOTENV_ONLY=example_value
VAR_WITH_NO_MATCHING_VALUE=example_value
VAR_WITH__ONE_LEVEL_NAMESPACE=example_value
VAR_WITH__TWO_LEVEL__NAMESPACE=example_value
VAR_WITH_INT_VALUE=example_value
VAR_WITH_NEGATIVE_INT_VALUE=example_value
VAR_WITH_TRUE_VALUE=example_value
VAR_WITH_FALSE_VALUE=example_value
DOTENV;
            $dotenvFile = <<<'DOTENV'
VAR_IN_ENVIRONMENT_AND_DOTENV_FILE=Doge
VAR_IN_DOTENV_ONLY=Trinity
VAR_NOT_IN_DOTENV_EXAMPLE_FILE="This should be ignored"
VAR_WITH__ONE_LEVEL_NAMESPACE=Doggo
VAR_WITH__TWO_LEVEL__NAMESPACE="Moon Moon"
VAR_WITH_INT_VALUE=9000
VAR_WITH_NEGATIVE_INT_VALUE=-273
VAR_WITH_TRUE_VALUE=true
VAR_WITH_FALSE_VALUE=false
DOTENV;
            if ($addInvalidEntry) {
                $dotenvExampleFile .= "\nThiS_IS_NOT_Right=example_value";
                $dotenvFile        .= "\nThiS_IS_NOT_Right=Whatever";
            }
            $tmpDir    = vfsStream::setup();
            $structure = [
                '.env'         => $dotenvFile,
                '.env.example' => $dotenvExampleFile,
            ];
            if (!$createExampleFile) {
                unset($structure['.env.example']);
            }
            vfsStream::create($structure, $tmpDir);
            $this->tmpPath = $tmpDir->url();
            putenv('VAR_IN_ENVIRONMENT_AND_DOTENV_FILE=Morpheus');
            putenv('VAR_NOT_IN_DOTENV_EXAMPLE_FILE="This should be ignored too"');

            return new EnvVarConfigParser(
                $createExampleFile ? "{$this->tmpPath}/.env.example" : "{$this->tmpPath}/null",
                $this->tmpPath,
                $baseParams
            );
        };
    });

    describe('-->parseConfig()', function () {
        it('imports the variables described in the `.env.example` file', function () {
            $params = $this->subjectFactory()->parseConfig();
            foreach ([
                ['VAR_IN_ENVIRONMENT_AND_DOTENV_FILE', 'var_in_environment_and_dotenv_file', 'Morpheus', null],
                ['VAR_IN_DOTENV_ONLY', 'var_in_dotenv_only', 'Trinity', null],
                ['VAR_WITH_NO_MATCHING_VALUE', 'var_with_no_matching_value', null, null],
                ['VAR_WITH__ONE_LEVEL_NAMESPACE', 'var_with.one_level_namespace', 'Doggo', null],
                ['VAR_WITH__TWO_LEVEL__NAMESPACE', 'var_with.two_level.namespace', 'Moon Moon', null],
                ['VAR_WITH_INT_VALUE', 'var_with_int_value', '9000', 9000],
                ['VAR_WITH_NEGATIVE_INT_VALUE', 'var_with_negative_int_value', '-273', -273],
                ['VAR_WITH_TRUE_VALUE', 'var_with_true_value', 'true', true],
                ['VAR_WITH_FALSE_VALUE', 'var_with_false_value', 'false', false],
            ] as $example) {
                list($envVarName, $paramName, $envValue, $paramValue) = $example;
                expect(getenv($envVarName) ?: null)->to->equal($envValue);
                expect($params)->to->contain->keys([$paramName]);
                expect($params[$paramName])->to->equal(!is_null($paramValue) ? $paramValue : $envValue);
            }
        });

        it('doesn’t import variables if they are not described in the `.env.example` file', function () {
            expect($this->subjectFactory()->parseConfig())->to->not->contain->keys(['var_not_in_dotenv_example_file']);
        });

        it('doesn’t override existing parameters', function () {
            $key    = 'var_with.one_level_namespace';
            $value  = 'Goofy';
            $params = $this->subjectFactory(true, false, [$key => $value])->parseConfig();
            expect($params[$key])->to->equal($value);
        });

        it('doesn’t import variables if there’s no `.env.example` file', function () {
            expect($this->subjectFactory(false)->parseConfig())->to->equal([]);
        });

        it('throws an exception when an environment variable name is invalid', function () {
            expect([$this->subjectFactory(true, true), 'parseConfig'])->to->throw(\RuntimeException::class);
        });
    });
});