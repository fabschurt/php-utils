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

use function Stringy\create as s;

/**
 * This implementation attempts to stick to what human beings would generally
 * expect when sorting a list of strings.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
final class NaturallySortableStringArray implements SortableStringArrayInterface
{
    /**
     * @var string[] The wrapped array of strings
     */
    private $wrappedArray;

    /**
     * @param string[] $wrappedArray A wrapped array of strings
     *
     * @throws \InvalidArgumentException If `$wrappedArray` does not contain strings only
     */
    public function __construct(array $wrappedArray)
    {
        foreach ($wrappedArray as $element) {
            if (!is_string($element)) {
                throw new \InvalidArgumentException('The passed array must contain strings only.');
            }
        }
        $this->wrappedArray = $wrappedArray;
    }

    /**
     * {@inheritDoc}
     */
    public function sortByTermResemblance($term)
    {
        $term  = (string) s($term)->toLowerCase();
        $array = $this->asArray();
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

    /**
     * {@inheritDoc}
     */
    public function asArray()
    {
        return $this->wrappedArray;
    }
}
