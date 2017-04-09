<?php

namespace Gielfeldt\Iterators;

/**
 * Glob iterator with double wildcard (**) and recursive capabilities.
 */
class GlobIterator extends \IteratorIterator
{
    const GLOB_NOSORT = 2048;

    /**
     * Constructor.
     *
     * @param string $globPattern
     *   Glob pattern.
     * @param integer $flags
     *   FilesystemIterator flags.
     */
    public function __construct(string $globPattern, int $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO)
    {
        list($path, $maxDepth) = self::extractPathAndMaxDepth($globPattern);
        $regexPattern = self::globToRegex($globPattern);

        $realPath = realpath($path ?? './');
        $realPath = rtrim($realPath, '/') . '/';

        $iterator = new \RecursiveDirectoryIterator($realPath, $flags);

        $sIterator = $flags & self::GLOB_NOSORT ? $iterator : new RecursiveSortIterator($iterator, SortIterator::SORT_SPL_FILE_INFO, 0);
        $rIterator = new \RecursiveIteratorIterator($sIterator);
        $rIterator->setMaxDepth($maxDepth);

        $fIterator = new \CallbackFilterIterator($rIterator, function ($current, $key, $iterator) use ($path, $realPath, $regexPattern, $flags) {
            $pathName = $flags & \FilesystemIterator::CURRENT_AS_PATHNAME ? $current : $current->getPathname();
            $pathName = $path . substr($pathName, strlen($realPath));
            if (!isset($path)) {
                $pathName = substr($pathName, 2);
            }
            return preg_match($regexPattern, $pathName);
        });
        parent::__construct($fIterator);
    }

    /**
     * Extract the path to start at.
     *
     * @param string $globPattern
     * @return string
     */
    static public function extractPathAndMaxDepth(string $globPattern): array
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
     * @param string $globPattern
     * @return string
     */
    static public function globToRegexOld(string $globPattern): string
    {
        $regexPattern = str_replace('.', '\\.', $globPattern);
        $regexPattern = preg_replace_callback('/\*+/', function ($matches) {
            return strlen($matches[0]) > 1 ? '.*' : '[^/]*';
        }, $regexPattern);
        $regexPattern = str_replace('@', '\\@', $regexPattern);
        return '@^' . $regexPattern . '$@s';
    }

    /**
     * Convert a glob pattern to a regex pattern.
     *
     * @param string $globPattern
     * @return string
     */
    static public function globToRegex(string $globPattern): string
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
            '\\'    => '\\\\'
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
    static public function registerStreamWrapper()
    {
        stream_wrapper_unregister('glob');
        stream_wrapper_register('glob', GlobStreamWrapper::class);
    }

    /**
     * Restore the default glob:// stream wrapper.
     */
    static public function unRegisterStreamWrapper()
    {
        stream_wrapper_unregister('glob');
        stream_wrapper_restore('glob');
    }
}
