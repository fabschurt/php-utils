<?php

use FabSchurt\PhpUtils\Iterator\ReadOnlyArrayIterator;

describe(NaturalStringArraySorter::class, function () {
    it('should provide one-way, read-only iteration over an interal array', function () {
        $objectArray = [
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
        ];
        $subject = new ReadOnlyArrayIterator($objectArray);
        $i       = 0;
        foreach ($subject as $object) {
            expect(spl_object_hash($objectArray[$i]))->to->equal(spl_object_hash($object));
            ++$i;
        }
    });
});
