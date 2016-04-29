<?php

/*
 * This file is part of the fabschurt/php-utils package.
 *
 * (c) 2016 Fabien Schurter <fabien@fabschurt.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FabSchurt\PhpUtils\Iterator;

/**
 * This `\Iterator` implementation acts as a wrapper around a normal PHP array
 * to provide read-only iteration possibilities over it.
 *
 * The immutability of the source array is guaranteed by the API, as it doesn't
 * expose any way to mutate the internal data.
 *
 * @author Fabien Schurter <fabien@fabschurt.com>
 */
class ReadOnlyArrayIterator implements \Iterator
{
    /**
     * @var array The iterator's internal data
     */
    private $data;

    /**
     * @var int The iterator's index pointer
     */
    private $pointer;

    /**
     * @param array $data The array that will be wrapped by this iterator instance
     */
    public function __construct(array $data)
    {
        $this->data    = $data;
        $this->pointer = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->data[$this->pointer];
    }

    /**
     * {@inheritDoc}
     */
    public function key(): int
    {
        return $this->pointer;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return isset($this->data[$this->pointer]);
    }
}
