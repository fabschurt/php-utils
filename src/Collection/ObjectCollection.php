<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\PhpUtils\Collection;

use FabSchurt\PhpUtils\Iterator\ReadOnlyArrayIterator;

/**
 * A simple collection class that wraps an internal array of objects that must
 * share the same type.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
class ObjectCollection implements \IteratorAggregate
{
    /**
     * @var object[] An array of same-type objects
     */
    private $data;

    /**
     * @var \Traversable A cached external iterator instance
     */
    private $memoizedIterator;

    /**
     * @param string   $type The expected FQCN of the collected objects (the class should exist and be autoloadable)
     * @param object[] $data An array of `$type` objects
     *
     * @throws \InvalidArgumentException If `$type` class doesn't exist or can't be autoloaded
     * @throws \InvalidArgumentException If all elements in `$data` are not instances of `$type`
     */
    public function __construct(string $type, array $data)
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(
                "Class `{$type}` doesn't exist or can't be autoloaded."
            );
        }
        foreach ($data as $object) {
            if (!is_a($object, $type)) {
                throw new \InvalidArgumentException(
                    "All elements of the passed array must be instances of `{$type}`."
                );
            }
        }

        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        if (!$this->memoizedIterator) {
            $this->memoizedIterator = new ReadOnlyArrayIterator($this->data);
        }

        return $this->memoizedIterator;
    }
}
