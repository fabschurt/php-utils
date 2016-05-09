<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\PhpUtils\ArrayUtils;

/**
 * This helper abstract implementation of `SortableStringArrayInterface` ensures
 * that the wrapped array indeed contains strings only, and provides a default
 * implementation for the `asArray()` method.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
abstract class AbstractSortableStringArray implements SortableStringArrayInterface
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
    public function asArray(): array
    {
        return $this->wrappedArray;
    }
}
