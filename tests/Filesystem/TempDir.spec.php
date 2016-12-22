<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FabSchurt\Php\Utils\Filesystem\TempDir;
use Symfony\Component\Filesystem\Filesystem;

describe(TempDir::class, function () {
    beforeEach(function () {
        $this->filesystem = new Filesystem();
        $this->subjectFactory = function ($prefix = null) {
            $args = [$this->filesystem];
            if ($prefix) {
                $args[] = $prefix;
            }
            $subject = new TempDir(...$args);
            $this->currentPath = (string) $subject;

            return $subject;
        };
    });

    afterEach(function () {
        if (!empty($this->currentPath) && $this->currentPath !== '/') {
            $this->filesystem->remove($this->currentPath);
        }
    });

    describe('->__toString()', function () {
        it('returns the path to the temporary directory on the filesystem, lazy-creating it on the fly', function () {
            expect(is_dir((string) $this->subjectFactory()))->to->be->true;
        });

        it('can add a custom prefix to the directory name', function () {
            $prefix = 'fortytwo.';
            expect(
                basename((string) $this->subjectFactory($prefix))
            )->to->match(
                sprintf('/^%s/', preg_quote($prefix))
            );
        });
    });

    describe('->prefixPath()', function () {
        it('prefixes the passed relative path with the absolute path to the temporary directory', function () {
            $subject = $this->subjectFactory();
            expect($subject->prefixPath('yep/nope'))->to->equal("{$subject}/yep/nope");
        });

        it('strips leading slashes form the passed relative path', function () {
            $subject = $this->subjectFactory();
            expect($subject->prefixPath('///yep/nope'))->to->equal("{$subject}/yep/nope");
        });
    });

    describe('->addStructure()', function () {
        it('creates a child filesystem tree from a data array with predefined format', function () {
            $subject = $this->subjectFactory();
            $structure = [
                'app' => [
                    'controllers' => [
                        'DefaultController.php' => 'This is not the controller you’re looking for.',
                        'ArticleController.php' => 'I had a dream, that this controller existed.',
                    ],
                ],
                'var' => [
                    'cache' => [],
                ],
                '.env' => 'Save the environment!',
            ];
            $subject->addStructure($structure);
            $baseDir = (string) $subject;
            expect(is_dir("{$baseDir}/var/cache"))->to->be->true;
            expect(
                file_get_contents("{$baseDir}/app/controllers/DefaultController.php")
            )->to->equal('This is not the controller you’re looking for.');
            expect(
                file_get_contents("{$baseDir}/app/controllers/ArticleController.php")
            )->to->equal('I had a dream, that this controller existed.');
            expect(file_get_contents("{$baseDir}/.env"))->to->equal('Save the environment!');
        });
    });

    describe('->cleanup()', function () {
        it('destroys the temporary directory on the filesystem', function () {
            $subject = $this->subjectFactory();
            $path    = (string) $subject;
            $subject->cleanup();
            expect(file_exists($path))->to->be->false;
        });
    });
});
