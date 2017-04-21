<?php

namespace Gielfeldt\Iterators;

class RandomIterator extends ReplaceableIterator
{
    private $count;
    private $innerIterator;

    public function __construct(\Traversable $iterator, int $count)
    {
        $this->innerIterator = new CountableIterator($iterator, CountableIterator::CACHE_COUNT);
        parent::__construct();
        $this->count = min($count, $this->innerIterator->count());
    }

    public function rewind()
    {
        $indexes = [];
        for ($i = 0; $i < $this->count; $i++) {
            do {
                $index = rand(0, $this->innerIterator->count());
            } while (isset($indexes[$index]));
            $indexes[$index] = $index;
        }
        var_dump($indexes);
    }


}
