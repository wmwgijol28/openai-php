<?php

declare(strict_types=1);

namespace OpenAI\Responses\Concerns;

use BadMethodCallException;

/**
 * @template TArray of array
 *
 * @mixin Response<TArray>
 */
trait ArrayAccessible
{
    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->toArray()[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset,$value)
    {
        throw new BadMethodCallException('Cannot set response attributes.');
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('Cannot unset response attributes.');
    }
}
