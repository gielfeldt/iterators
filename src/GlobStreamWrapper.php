<?php

namespace Gielfeldt\Iterators;

/**
 * Stream wrapper providing recursive glob functionality.
 */
class GlobStreamWrapper {

    protected $directoryIterator;

    protected static $root;

    public static function setRoot($root)
    {
        self::$root = $root;
    }

    protected static function getRootedPath($path)
    {
        // Avoid infinite recursion.
        if (strpos($path, 'glob://') === 0) {
            $path = substr($path, 7);
        }

        return self::$root . $path;
    }

    /**
     * @see streamWrapper::dir_opendir()
     */
    public function dir_opendir(string $path, int $options)
    {
        $path = self::getRootedPath($path);

        // Setup recursive glob iterator and rewind.
        $this->directoryIterator = new GlobIterator($path);
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
        $filename = $current ? str_replace($this->directoryIterator->getPath(), '', $current->getPathname()) : false;
        return $filename;
    }

    /**
     * @see streamWrapper::dir_rewinddir()
     */
    public function dir_rewinddir()
    {
        $this->directoryIterator->rewind();
        return true;
    }

    /**
     * @see streamWrapper::dir_closedir()
     */
    public function dir_closedir()
    {
        unset($this->directoryIterator);
        return true;
    }
}
