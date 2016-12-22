<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FabSchurt\Php\Utils\Arrays\ArraySorter;

describe(ArraySorter::class, function () {
    beforeEach(function () {
        $this->dataFactory = function () {
            return [
                'Thymus Serpillum Extrakt',
                'Thymol',
                'Thymus Vulgaris',
                'Thymus Mastichina Herb Oil',
            ];
        };
    });

    describe('::sortByTermResemblance()', function () {
        it('naturally orders an array according to resemblance with the passed term', function () {
            expect(
                ArraySorter::sortByTermResemblance($this->dataFactory(), 'THYMUS VULGARIS FLOWER WATER')
            )->to->equal([
                'Thymus Vulgaris',
                'Thymus Serpillum Extrakt',
                'Thymus Mastichina Herb Oil',
                'Thymol',
            ]);
        });
    });
});
