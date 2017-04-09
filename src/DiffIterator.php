<?php

namespace Gielfeldt\Iterators;

class DiffIterator extends \FilterIterator
{
    protected $iterators;
    protected $callback;

    public function __construct(\Traversable ...$iterators)
    {
        $innerIterator = array_shift($iterators);
        parent::__construct($innerIterator);
        foreach ($iterators as $iterator) {
            $this->iterators[] = new \CachingIterator($iterator, \CachingIterator::FULL_CACHE);
        }
        $this->callback = \Closure::fromCallable([static::class, 'diffCurrent']);
    }

    public function setDiff(callable $callback)
    {
        $this->callback = \Closure::fromCallable($callback);
    }

    public function accept() {
        foreach ($this->iterators as $iterator) {
            foreach ($iterator as $key => $value) {
                if (($this->callback)($this->getInnerIterator(), $key, $value)) {
                    return false;
                }
            }
        }
        return true;
	}

    protected static function diffCurrent($iterator, $key, $value)
    {
        return $iterator->current() == $value;
    }

    protected static function diffKey($iterator, $key, $value)
    {
        return $iterator->key() == $key;
    }
}
