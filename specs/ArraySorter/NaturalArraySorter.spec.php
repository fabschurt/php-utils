<?php

use FabSchurt\PhpUtils\ArraySorter\NaturalStringArraySorter;

describe(NaturalStringArraySorter::class, function () {
    describe('->sortStringArrayByTermResemblance()', function () {
        $subject = new NaturalStringArraySorter();
        it(
            'should order the passed string array according to resemblance with the passed term (longest common prefix strategy)',
            function () use ($subject) {
                expect($subject->sortStringArrayByTermResemblance(
                    [
                        'Thymus Serpillum Extrakt',
                        'Thymol',
                        'Thymus Vulgaris',
                        'Thymus Mastichina Herb Oil',
                    ],
                    'THYMUS VULGARIS FLOWER WATER'
                ))->to->equal([
                    'Thymus Vulgaris',
                    'Thymus Serpillum Extrakt',
                    'Thymus Mastichina Herb Oil',
                    'Thymol',
                ]);
            }
        );
    });
});
