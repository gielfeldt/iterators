<?php

namespace Gielfeldt\Iterators;

/**
 * Glob iterator with double wildcard (**) and recursive capabilities.
 */
class GlobIterator extends TraversableIterator implements \Countable
{
    const GLOB_NOSORT = 2048;

    protected $flags;
    protected $path;

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Constructor.
     *
     * @param string  $globPattern
     *   Glob pattern.
     * @param integer $flags
     *   FilesystemIterator flags.
     */
    public function __construct(string $globPattern, int $flags = self::GLOB_NOSORT | \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO)
    {
        $this->flags = $flags;
        list($path, $maxDepth) = self::extractPathAndMaxDepth($globPattern);
        $regexPattern = self::globToRegex($globPattern);

        $this->path = $path;

        $realPath = $path ? $path : './';
        $realPath = rtrim($realPath, '/') . '/';

        $flags = $flags & ~ \FilesystemIterator::CURRENT_AS_PATHNAME;
        $flags |= \FilesystemIterator::KEY_AS_PATHNAME;
        $iterator = new \RecursiveDirectoryIterator($realPath, $flags);

        // Sort if necessary.
        $sIterator = $this->flags & self::GLOB_NOSORT ? $iterator : new RecursiveSortIterator(
            $iterator,
            RecursiveSortIterator::SORT_ASC,
            0,
            [$this, 'sortSplFileInfo']
        );

        // Only traverse the depth needed.
        $rIterator = new \RecursiveIteratorIterator($sIterator, \RecursiveIteratorIterator::SELF_FIRST);
        $rIterator->setMaxDepth($maxDepth);

        // Setup file info handler.
        $iteratorId = spl_object_hash($this);
        GlobIteratorFileInfo::setPath($iteratorId, $path, $realPath);

        // Actual glob filtering.
        $fIterator = new \CallbackFilterIterator(
            $rIterator,
            function (&$current, &$key) use ($iteratorId, $regexPattern) {
                GlobIteratorFileInfo::setIteratorId($iteratorId);
                $fileInfo = $current->getFileInfo(GlobIteratorFileInfo::class);
                if ($this->flags & \FilesystemIterator::CURRENT_AS_PATHNAME) {
                    $current = $fileInfo->getPathname();
                } else {
                    $current = $fileInfo;
                }

                if ($this->flags & \FilesystemIterator::KEY_AS_FILENAME) {
                    $key = $fileInfo->getFilename();
                } else {
                    $key = $fileInfo->getPathname();
                }
                return preg_match($regexPattern, $fileInfo->getPathname());
            }
        );
        parent::__construct(new CountableIterator($fIterator, CountableIterator::CACHE_COUNT));
    }

    public function count()
    {
        return $this->getInnerIterator()->count();
    }

    public function sortSplFileInfo($cmpA, $cmpB)
    {
        return $cmpA->current->getPathname() <=> $cmpB->current->getPathname();
    }

    /**
     * Extract the path to start at.
     *
     * @param  string $globPattern
     * @return string
     */
    public static function extractPathAndMaxDepth(string $globPattern): array
    {
        if (strpos($globPattern, '*')) {
            list($path,) = explode('*', $globPattern, 2);
        }
        $path = isset($path) ? dirname("$path.") . '/' : '';
        $subPattern = substr($globPattern, strlen($path));
        $maxDepth = strpos($globPattern, '**') !== false ? -1 : substr_count($subPattern, '/');
        return [$path, $maxDepth];
    }

    /**
     * Convert a glob pattern to a regex pattern.
     *
     * @param  string $globPattern
     * @return string
     */
    public static function globToRegex(string $globPattern): string
    {
        $modifiers = '';
        $transforms = array(
            '\*'    => '[^/]*',
            '\*\*'    => '.*',
            '\?'    => '[^/]',
            '\[\!'    => '[^',
            '\['    => '[',
            '\]'    => ']',
            '\.'    => '\.',
        );
        $regexPattern = '#^'
            . strtr(preg_quote($globPattern, '#'), $transforms)
            . '$#'
            . $modifiers;

        return $regexPattern;
    }

    /**
     * Use the RecursiveGlobIterator for glob://
     */
    public static function registerStreamWrapper($root = null)
    {
        stream_wrapper_unregister('glob');
        stream_wrapper_register('glob', GlobStreamWrapper::class);
        GlobStreamWrapper::setRoot($root);
    }

    /**
     * Restore the default glob:// stream wrapper.
     */
    public static function unRegisterStreamWrapper()
    {
        stream_wrapper_unregister('glob');
        stream_wrapper_restore('glob');
        GlobStreamWrapper::setRoot(null);
    }
}
