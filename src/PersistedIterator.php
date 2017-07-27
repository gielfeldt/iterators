<?php

namespace Gielfeldt\Iterators;

class PersistedIterator implements \IteratorAggregate
{
    protected $tempFile;

    public function __construct(iterable $iterator = null)
    {
        $tempFilename = tempnam(\sys_get_temp_dir(), 'PersistedIterator');
        $this->tempFile = new \SplFileObject($tempFilename, 'w');
        if ($iterator) {
            $this->append($iterator);
        }
    }

    public function append(iterable $iterator)
    {
        foreach ($iterator as $key => $value) {
            $this->add($key, $value);
        }
    }

    public function add($key, $value)
    {
        $this->tempFile->fwrite(base64_encode(serialize([$key, $value])) . "\n");
    }

    public function __destruct()
    {
        $tempFilename = $this->tempFile->getRealPath();
        unset($this->tempFile);
        unlink($tempFilename);
    }

    public function getIterator()
    {
        $file = new \SplFileObject($this->tempFile->getRealPath());
        $file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD);
        $iterator = new class($file, function ($iterator) {
            return unserialize(base64_decode($iterator->current()));
        }) extends MapIterator {
            public $parent;
        };
        $iterator->parent = $this;
        return $iterator;
    }
}

class PersistedIterator2 implements \IteratorAggregate
{
    protected $tempFile;

    public function __construct(iterable $iterator = null)
    {
        $tempFilename = tempnam(\sys_get_temp_dir(), 'PersistedIterator');
        $this->tempFile = new \SplFileObject($tempFilename, 'w');
        if ($iterator) {
            $this->append($iterator);
        }
    }

    public function append(iterable $iterator)
    {
        foreach ($iterator as $key => $value) {
            $this->add($key, $value);
        }
    }

    public function add($key, $value)
    {
        $this->tempFile->fwrite(base64_encode(serialize([$key, $value])) . "\n");
    }

    public function __destruct()
    {
        $tempFilename = $this->tempFile->getRealPath();
        unset($this->tempFile);
        unlink($tempFilename);
    }

    public function getIterator()
    {
        $iterator = new class($this->tempFile->getRealPath()) extends \SplFileObject {
            public $parent;
            public function rewind() {
                parent::rewind();
                $this->collect();
            }
            public function next() {
                parent::next();
                $this->collect();
            }
            public function collect() {
                $this->valid = parent::valid();
                $this->current = unserialize(base64_decode(parent::current()));
            }
            public function key() { return $this->current[0]; }
            public function current() { return $this->current[1]; }
        };
        $iterator->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD);
        $iterator->parent = $this;
        return $iterator;
    }
}
