<?php

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
