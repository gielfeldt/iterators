<?php

namespace Gielfeldt\Iterators;

class GlobIteratorFileInfo extends \SplFileInfo
{
    private static $iteratorId;
    private static $paths = [];

    public function __construct($fileName)
    {
        $fileName = $this->processFilename($fileName);
        parent::__construct($fileName);
    }

    public function getIteratorId()
    {
        return self::$iteratorId;
    }

    public static function setIteratorId($iteratorId)
    {
        self::$iteratorId = $iteratorId;
    }

    public static function setPath($iteratorId, $path, $realPath)
    {
        self::$paths[$iteratorId] = [$path, $realPath];
    }

    protected function processFilename($fileName)
    {
        $iteratorId = self::getIteratorId();
        $pathName = self::$paths[$iteratorId][0] . substr($fileName, strlen(self::$paths[$iteratorId][1]));
        return $pathName;
    }
}
