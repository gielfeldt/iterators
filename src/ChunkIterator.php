<?php

namespace Gielfeldt\Iterators;

class ChunkIterator extends \ArrayIterator
{
    public function __construct(\Traversable $iterator, $size)
    {
        parent::__construct([]);
        if (!$iterator instanceof \Countable) {
            print "USE COUNTABLE!\n";
            $iterator = new CountableIterator($iterator);
        }
        $offset = 0;
        $count = count($iterator);
        var_dump($count);
        while ($count > 0) {
            $this->append(new \LimitIterator($iterator, $offset, $size));
            $offset += $size;
            $count -= $size;
        }
    }
}
