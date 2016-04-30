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

    describe('->getIterator()', function () {
        it('should return an iterator that provides read access to the internal object array', function () {
            $objectArray = [
                new \stdClass(),
                new \stdClass(),
                new \stdClass(),
            ];
            $collection = new ObjectCollection('\stdClass', $objectArray);
            $i          = 0;
            foreach ($collection->getIterator() as $collectedObject) {
                expect(spl_object_hash($objectArray[$i]))->to->equal(spl_object_hash($collectedObject));
                ++$i;
            }
        });
    });
});
