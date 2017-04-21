<?php

namespace Gielfeldt\Iterators;

class DebugIterator extends \IteratorIterator
{
    public function getInnerIterator()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::getInnerIterator();
    }

    public function valid()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::valid();
    }

    public function key()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::key();
    }

    public function current()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::current();
    }

    public function rewind()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::rewind();
    }

    public function next()
    {
        print static::class . '->' . __FUNCTION__ . "()\n";
        return parent::next();
    }

    public function __call($method, $arguments)
    {
        print static::class . '->' . $method . "()\n";
        $iterator = parent::getInnerIterator();
        if (!is_callable([$iterator, $method])) {
            throw new \BadMethodCallException("Method " . get_class($iterator) . '->' . $method . ' could not be called.');
        }
        return call_user_func_array([$iterator, $method], $arguments);
    }
}
