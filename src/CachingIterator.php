<?php

namespace Gielfeldt\Iterators;

class CachingIterator extends \ArrayIterator
{
    const CLONE_KEY = 1;
    const CLONE_CURRENT = 2;

    private $uncachedIterator;
    private $uncachedIteratorCount;
    private $modified = false;
    private $finished = false;
    private $flags;

    public function __construct(\Traversable $iterator, int $flags = self::CLONE_KEY | self::CLONE_CURRENT)
    {
        $this->flags = $flags;
        $this->uncachedIteratorCount = $iterator instanceof \Countable ? count($iterator) : null;
        $this->uncachedIterator = new IteratorIterator($iterator);
        parent::__construct();
    }

    public function count()
    {
        if (!$this->finished && !$this->modified && $this->uncachedIteratorCount !== null) {
            return $this->uncachedIteratorCount;
        }
        $this->collectRest();
        return parent::count();
    }

    private function setupInnerIterator()
    {
        if ($this->uncachedIterator->getIndex() === null) {
            $this->uncachedIterator->rewind();
            parent::rewind();
            $this->collect();
        }
    }

    public function rewind()
    {
        $this->setupInnerIterator();
        parent::rewind();
    }

    public function next()
    {
        parent::next();
        if (!parent::valid()) {
            $this->collect();
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->collectRest();
        $this->modified = true;
        return parent::offsetSet($offset, $value);
    }

    public function collectRest($until = null)
    {
        if ($this->finished) {
            return;
        }

        $this->setupInnerIterator();

        while (!$this->finished && ($until == null || $until >= $this->uncachedIterator->getIndex())) {
            $this->collect();
        }
    }

    public function collect()
    {
        if ($this->uncachedIterator->valid()) {
            $key = $this->uncachedIterator->key();
            $key = ($this->flags & self::CLONE_KEY) && is_object($key) ? clone $key : $key;
            $current = $this->uncachedIterator->current();
            $current = ($this->flags & self::CLONE_CURRENT) && is_object($current) ? clone $current : $current;
            parent::offsetSet($key, $current);
            $this->uncachedIterator->next();
        } else {
            $this->finished = true;
        }
    }

    // Ensure entire inner iterator is collected before applying the follwing.
    public function seek($pos)
    {
        $this->collectRest($pos);
        return parent::seek($pos);
    }

    public function offsetGet($offset)
    {
        $this->collectRest();
        return parent::offsetGet($offset);
    }

    public function offsetExists($offset)
    {
        $this->collectRest();
        return parent::offsetExists($offset);
    }

    public function offsetUnset($offset)
    {
        $this->collectRest();
        $this->modified = true;
        return parent::offsetUnset($offset);
    }

    public function getArrayCopy()
    {
        $this->collectRest();
        return iterator_to_array($this);
    }

    public function ksort()
    {
        $this->collectRest();
        return parent::ksort();
    }
    public function natcasesort()
    {
        $this->collectRest();
        return parent::natcasesort();
    }
    public function natsort()
    {
        $this->collectRest();
        return parent::natsort();
    }
    public function uasort($cmp)
    {
        $this->collectRest();
        return parent::uasort($cmp);
    }
    public function uksort($cmp)
    {
        $this->collectRest();
        return parent::uksort($cmp);
    }
    public function serialize()
    {
        $this->collectRest();
        $serialized = "x:i:0;";
        $serialized .= serialize($this->getArrayCopy()) . ";";
        $serialized .= "m:a:0:{}";
        return $serialized;
    }
    public function unserialize($serialized)
    {
        $this->finished = true;
        $this->modified = false;
        $this->uncachedIterator = new IteratorIterator(new \EmptyIterator());
        $this->uncachedIteratorCount = null;
        return parent::unserialize($serialized);
    }
}
