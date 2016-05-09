<?php

use FabSchurt\PhpUtils\Collection\ObjectCollection;

describe(ObjectCollection::class, function () {
    describe('->__construct()', function () {
        it('should choke if the expected FQCN doesn\'t exist', function () {
            expect(function () {
                new ObjectCollection(uniqid(), []);
            })->to->throw('\InvalidArgumentException');
        });

        it('should choke if all objects in the passed array are not of the expected type', function () {
            expect(function () {
                new ObjectCollection('\stdClass', [new \stdClass(), new \Exception()]);
            })->to->throw('\InvalidArgumentException');
        });
    });

    describe('->asArray()', function () {
        it('should return a copy of the internal array', function () {
            $objectArray = get_example_array();
            $subject     = new ObjectCollection('\stdClass', $objectArray);
            $i           = 0;
            expect(count($objectArray))->to->equal(count($subject->asArray()));
            foreach ($subject->asArray() as $collectedObject) {
                expect($objectArray[$i])->to->equal($collectedObject);
                ++$i;
            }
        });
    });
});
