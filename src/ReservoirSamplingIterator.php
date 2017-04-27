<?php

namespace Gielfeldt\Iterators;

class ReservoirSamplingIterator implements \IteratorAggregate
{
    private $count;
    private $iterator;

    public function __construct(\Traversable $iterator, int $count)
    {
        $this->iterator = new TraversableIterator($iterator);
        $this->count = $count;
    }

    public function getIterator()
    {
        $result = new \ArrayIterator();
        $this->iterator->rewind();
        while ($this->iterator->getIndex() < $this->count && $this->iterator->valid()) {
            $result->append($this->iterator->current());
            $this->iterator->next();
        }
        while ($this->iterator->valid()) {
            $newIndex = (int) rand(0, $this->iterator->getIndex());
            if ($newIndex < $this->count) {
                $result[$newIndex] = $this->iterator->current();
            }
            $this->iterator->next();
        }
        return $result;
    }
}
