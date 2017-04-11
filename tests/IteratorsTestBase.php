<?php
namespace Gielfeldt\Tests\Iterators;

class IteratorsTestBase extends \PHPUnit\Framework\TestCase
{
    public $fixturesPath = __DIR__ . '/fixtures';
    public $tempFiles = [];
    public $tempDirs = [];

    public function tearDown()
    {
        $this->cleanupTempFiles();
    }

    /**
     * Cleanup our temporary files.
     */
    public function cleanupTempFiles()
    {
        foreach ($this->tempFiles as $tmpFile) {
            @unlink($tmpFile);
        }
        $this->tempFiles = [];

        foreach ($this->tempDirs as $tmpDir) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tmpDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            @rmdir($tmpDir);
        }
        $this->tempDirs = [];
    }

    /**
     * Like tempnam() but with sane defaults.
     *
     * @see tempnam()
     */
    public function tempnam($path = null, $prefix = 'IteratorsTest')
    {
        $path = $path ?? sys_get_temp_dir();
        $filename = tempnam($path, $prefix);
        unlink($filename);
        $this->tempFiles[] = $filename;
        return $filename;
    }

    /**
     * Like tempnam() but for directories.
     *
     * @see tempnam()
     */
    public function tempdirnam($path = null, $prefix = 'IteratorsTest')
    {
        $path = $path ?? sys_get_temp_dir();
        $dirname = tempnam($path, $prefix);
        unlink($dirname);
        $dirname .= '.dir';
        $this->tempDirs[] = $dirname;
        return $dirname;
    }
}
