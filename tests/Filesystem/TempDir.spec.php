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

        $this->subjectFactory = function (string $prefix = ''): TempDir {
            $args = array_merge(
                [$this->filesystem],
                $prefix ? [$prefix] : []
            );
            $subject           = new TempDir(...$args);
            $this->currentPath = (string) $subject;

            return $subject;
        };
    });

    afterEach(function () {
        if ($this->currentPath) {
            $this->filesystem->remove($this->currentPath);
        }
    });

    describe('->__toString()', function () {
        it('returns the path to the temporary directory on the filesystem, lazy-creating it on the fly', function () {
            expect(is_dir((string) $this->subjectFactory()))->to->be->true;
        });

        it('can add a custom prefix to the directory name', function () {
            $prefix = 'fortytwo-';
            expect(basename((string) $this->subjectFactory($prefix)))->to->match("/^{$prefix}/");
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

    describe('->cleanup()', function () {
        it('destroys the temporary directory on the filesystem', function () {
            $subject = $this->subjectFactory();
            $path    = (string) $subject;
            $subject->cleanup();
            expect(file_exists($path))->to->be->false;
        });
    });
});
