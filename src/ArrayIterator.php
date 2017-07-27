<?php

namespace Gielfeldt\Iterators;

class ArrayIterator implements \SeekableIterator, \ArrayAccess, \Countable
{
    private $count = 0;
    private $storage = [];
    private $hashMap = [];
    private $index = 0;
    private $numericIndex = 0;

    public function __construct(iterable $source)
    {
        $this->count = 0;
        foreach ($source as $key => $value) {
            $this->append($key, $value);
        }
    }

    private function generateHashMapKey($key)
    {
        if ($key == null) {
            return $this->numericIndex;
        }
        return is_scalar($key) ? $key : (is_object($key) ? spl_object_hash($key) : serialize($key));
    }

    public function append($key, $value)
    {
        $this->appendElement($key, $value, $this->generateHashMapKey($key));
    }

    private function appendElement($key, $value, $hashKey)
    {
        if ($key == null) {
            $key = $this->numericIndex;
        }
        if (is_numeric($key) && $key > $this->numericIndex) {
            $this->numericIndex = $key + 1;
        }
        $this->storage[$this->count] = [$key, $value];
        $this->hashMap[$hashKey][] = &$this->storage[$this->count];
        $this->count++;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function count()
    {
        return $this->count;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->storage[$this->index][0] ?? null;
    }

    public function current()
    {
        return $this->storage[$this->index][1] ?? null;
    }

    public function seek($pos)
    {
        if ($pos < 0 || $pos >= $this->count) {
            throw new \OutOfBoundsException("Cannot seek to $pos");
        }
        $this->index = $pos;
    }

    public function valid()
    {
        return $this->index < $this->count;
    }

    public function &offsetGet($offset)
    {
        $key = $this->generateHashMapKey($offset);
        return reset($this->hashMap[$key])[1];
    }

    public function offsetExists($offset)
    {
        $key = $this->generateHashMapKey($offset);
        return isset($this->hashMap[$key]);
    }

    public function offsetSet($offset, $value)
    {
        $key = $this->generateHashMapKey($offset);
        if (!isset($this->hashMap[$key])) {
            $this->appendElement($offset, $value, $key);
        }
        return $this->hashMap[$key];
    }

    public function offsetUnset($offset)
    {
        $key = $this->generateHashMapKey($offset);
        $this->count -= isset($this->hashMap[$key]) ? count($this->hashMap[$key]) : 0;
        unset($this->hashMap[$key]);
    }
}
