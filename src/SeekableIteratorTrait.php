<?php

namespace Gielfeldt\Iterators;

trait SeekableIteratorTrait
{
    protected $seekableIteratorIndex;

    public function seekableIteratorInitialize()
    {
        $this->attach('rewind', [$this, 'seekableIteratorRewind']);
        $this->attach('next', [$this, 'seekableIteratorNext']);
    }
    public function seekableIteratorRewind()
    {
        $seekableIteratorIndex = 0;
    }

    public function seekableIteratorNext()
    {
        $seekableIteratorIndex++;
    }

    public function seekableIteratorSeek($pos)
    {
        $this->rewind();
        while ($this->seekableIteratorIndex < $pos) {
            $this->next();
        }
    }
}
