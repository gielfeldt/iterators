<?php

namespace Gielfeldt\Iterators;

/**
 * Stream wrapper providing recursive glob functionality.
 */
class GlobStreamWrapper {

    protected $directoryIterator;

    /**
     * @see streamWrapper::dir_opendir()
     */
    public function dir_opendir(string $path, int $options)
    {
        // Avoid infinite recursion.
        if (strpos($path, 'glob://') === 0) {
            $path = substr($path, 7);
        }

        // Setup recursive glob iterator and rewind.
        $this->directoryIterator = new \IteratorIterator(new GlobIterator($path));
        $this->directoryIterator->rewind();
        return true;
    }

    /**
     * @see streamWrapper::dir_readdir()
     */
    public function dir_readdir()
    {
        $current = $this->directoryIterator->current();
        $this->directoryIterator->next();
        return $current ? $current : false;
    }

    /**
     * @see streamWrapper::dir_rewinddir()
     */
    public function dir_rewinddir()
    {
        $this->directoryIterator->rewind();
        return true;
    }
}
