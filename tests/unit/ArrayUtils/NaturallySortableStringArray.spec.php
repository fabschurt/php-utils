<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FabSchurt\PhpUtils\ArrayUtils\NaturallySortableStringArray;

describe(NaturallySortableStringArray::class, function () {
    describe('->sortByTermResemblance()', function () {
        it(
            'should naturally order its elements according to resemblance with the passed term',
            function () {
                $subject = new NaturallySortableStringArray([
                    'Thymus Serpillum Extrakt',
                    'Thymol',
                    'Thymus Vulgaris',
                    'Thymus Mastichina Herb Oil',
                ]);
                expect($subject->sortByTermResemblance('THYMUS VULGARIS FLOWER WATER'))->to->equal([
                    'Thymus Vulgaris',
                    'Thymus Serpillum Extrakt',
                    'Thymus Mastichina Herb Oil',
                    'Thymol',
                ]);
            }
        );
    });
});
