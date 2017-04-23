<?php

namespace Gielfeldt\Iterators;

class ChecksumIterator extends IteratorIterator
{
    protected $algo;

    public function __construct(\Traversable $iterator, $algo = 'sha256')
    {
        $this->algo = $algo;
        $this->callback = \Closure::fromCallable([static::class, 'serializeCurrent']);
        parent::__construct($iterator);
    }

    public static function serializeCurrent($iterator) {
        $current = $iterator->current();
        return is_scalar($current) ? $current : serialize($current);
    }

    public function setSerializer(callable $callback)
    {
        $this->callback = \Closure::fromCallable($callback);
    }

    public function current() {
        return hash($this->algo, ($this->callback)($this->getInnerIterator()));
    }

    public function getChecksum()
    {
        $ctx = hash_init($this->algo);
        $this->rewind();
        while ($this->valid()) {
            hash_update($ctx, ($this->callback)($this));
            $this->next();
        }
        return hash_final($ctx);
    }

    public function __toString()
    {
        return $this->getChecksum();
    }
}
