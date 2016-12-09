<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FabSchurt\Php\Utils\SortableArray\NaturallySortableStringArray;

describe(NaturallySortableStringArray::class, function () {
    beforeEach(function () {
        $this->dataFactory = function () {
            return [
                'Thymus Serpillum Extrakt',
                'Thymol',
                'Thymus Vulgaris',
                'Thymus Mastichina Herb Oil',
            ];
        };

        $this->subjectFactory = function (array $data = null) {
            return new NaturallySortableStringArray($data ? $data : $this->dataFactory());
        };
    });

    describe('->sortByTermResemblance()', function () {
        it('naturally orders its elements according to resemblance with the passed term', function () {
            expect($this->subjectFactory()->sortByTermResemblance('THYMUS VULGARIS FLOWER WATER'))
                ->to->equal([
                    'Thymus Vulgaris',
                    'Thymus Serpillum Extrakt',
                    'Thymus Mastichina Herb Oil',
                    'Thymol',
                ])
            ;
        });
    });

    describe('->asArray()', function () {
        it('returns the wrapped source array untouched', function () {
            $data = $this->dataFactory();
            expect($this->subjectFactory($data)->asArray())->to->equal($data);
        });
    });
});
