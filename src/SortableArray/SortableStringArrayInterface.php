<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\Php\Utils\SortableArray;

/**
 * Implementations of this interface are expected to behave like wrappers which
 * decorate source string arrays with various sorting capabilities.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
interface SortableStringArrayInterface
{
    /**
     * Returns a copy of the internal array sorted according to resemblance with
     * the passed term.
     *
     * @param string $term The term according to which the string array should be sorted
     *
     * @return string[] The sorted string array
     */
    public function sortByTermResemblance($term);

    /**
     * Returns the wrapped array untouched.
     *
     * @return string[]
     */
    public function asArray();
}
