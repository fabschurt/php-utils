<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\Php\Utils\Arrays;

use function Stringy\create as s;

/**
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
final class ArraySorter
{
    /**
     * Sorts an array in order of resemblance with the passed term.
     *
     * This is mainly relevant for string arrays, even though any other type
     * will be type-casted to string anyway.
     *
     * @param array  $array The array to be sorted
     * @param string $term  The term which the array should be sorted against
     *
     * @return string[] The sorted array
     */
    public static function sortByTermResemblance(array $array, $term)
    {
        $term = (string) s($term)->toLowerCase();
        usort($array, function ($a, $b) use ($term) {
            $aScore = s($a)->toLowerCase()->longestCommonPrefix($term)->length();
            $bScore = s($b)->toLowerCase()->longestCommonPrefix($term)->length();
            if ($aScore === $bScore) {
                return 0;
            }

            return $aScore > $bScore ? -1 : 1;
        });

        return $array;
    }
}
