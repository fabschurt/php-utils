<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\PhpUtils\ArraySorter;

use function Stringy\create as s;

/**
 * This implementation attempts to stick to what human beings would generally
 * expect when sorting a list of things.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
class NaturalStringArraySorter implements StringArraySorterInterface
{
    /**
     * {@inheritDoc}
     */
    public function sortStringArrayByTermResemblance(array $array, string $term): array
    {
        $term = (string) s($term)->toLowerCase();
        usort($array, function (string $a, string $b) use ($term): int {
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
