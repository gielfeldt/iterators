<?php

namespace Gielfeldt\Iterators;

class CountableIterator extends ReplaceableIterator implements \Countable
{
    const CACHE_COUNT = 1;

    private $idx;
    private $count;
    private $flags;

    public function __construct(\Traversable $iterator, $flags = self::CACHE_COUNT)
    {
        $this->flags = $flags;
        parent::__construct($iterator);
    }

    public function rewind()
    {
        $this->idx = 0;
        parent::rewind();
    }

    public function next()
    {
        parent::next();
        $this->idx++;
    }

    public function count()
    {
        if (!($this->flags & self::CACHE_COUNT) || !isset($this->count)) {
            if ($this->getInnerIterator() instanceof \Countable) {
                print "Is countable!\n";
                $this->count = intval($this->getInnerIterator()->count());
            }
            else {
                print "Is NOT countable!\n";
                if (!isset($this->idx)) {
                    print "REWINDING!\n";
                    $this->rewind();
                }
                print "LOOPING THROUGH!\n";
                while ($this->valid()) {
                    print "IN LOOP!\n";
                    $this->next();
                }
                print "DONE!\n";
                $this->count = $this->idx;
            }
        }
        return $this->count;
    }
}
