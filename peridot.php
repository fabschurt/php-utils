<?php

/**
 * @return object[]
 */
function get_example_array(): array
{
    $array = [];
    for ($i = 0; $i < 3; ++$i) {
        $object = new stdClass();
        $object->name = "Object{$i}";
        $array[$i] = $object;
    }

    return $array;
}
