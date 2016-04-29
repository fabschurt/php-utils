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

/**
 * Implementations of this interface are expected to provide various array
 * sorting facilities.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
interface StringArraySorterInterface
{
    /**
     * Sorts an array of strings according to resemblance with a passed term.
     *
     * @param string[] $array The string array to sort
     * @param string   $term  The term according to which the string array should be sorted
     *
     * @return string[] The sorted string array
     */
    public function sortStringArrayByTermResemblance(array $array, string $term): array;
}
