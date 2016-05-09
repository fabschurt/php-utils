<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FabSchurt\PhpUtils\ArrayUtils\AbstractSortableStringArray;

describe(AbstractSortableStringArray::class, function () {
    describe('->__construct()', function () {
        it('should choke if the passed array does not contain strings only', function () {
            expect(function () {
                $mixedArray = [
                    'string',
                    42,
                    'other_string',
                ];
                Phake::partialMock(AbstractSortableStringArray::class, $mixedArray);
            })->to->throw('\InvalidArgumentException');
        });
    });

    describe('->asArray()', function () {
        it('should return a copy of the internal array', function () {
            $stringArray = [
                'one',
                'two',
                'three',
            ];
            $subject = Phake::partialMock(AbstractSortableStringArray::class, $stringArray);
            expect($subject->asArray())->to->equal($stringArray);
        });
    });
});
