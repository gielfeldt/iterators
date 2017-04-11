<?php

namespace Gielfeldt\Iterators;

class GlobIteratorFileInfo extends \SplFileInfo {
    private static $id;
    private static $paths = [];

    public function __construct($file_name) {
        $file_name = $this->processFilename($file_name);
        parent::__construct($file_name);
    }

    public function getId()
    {
        return self::$id;
    }

    public static function setId($id)
    {
        self::$id = $id;
    }

    public static function setPath($id, $path, $realPath)
    {
        self::$paths[$id] = [$path, $realPath];
    }

    protected function processFilename($file_name) {
        $id = self::getId();
        $pathName = self::$paths[$id][0] . substr($file_name, strlen(self::$paths[$id][1]));
        if (!isset(self::$paths[$id][0])) {
            $pathName = substr($pathName, 2);
        }
        return $pathName;
    }
}
