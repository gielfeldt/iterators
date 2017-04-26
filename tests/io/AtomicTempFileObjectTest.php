<?php
namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\AtomicTempFileObject;

class AtomicTempFileObjectTest extends IteratorsTestBase
{
    public function testAutoCreateDirectory()
    {
        $filename = $this->tempnam();
        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $this->assertEquals(5, filesize($filename), 'File is not correctly persisted - size.');
        $this->assertEquals("TEST1", file_get_contents($filename), 'File is not correctly persisted - content.');
    }

    public function testPersist()
    {
        $filename = $this->tempnam();
        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $this->assertEquals(5, filesize($filename), 'File is not correctly persisted - size.');
        $this->assertEquals("TEST1", file_get_contents($filename), 'File is not correctly persisted - content.');
    }

    public function testPersistIfChanged()
    {
        $filename = $this->tempnam();

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $originalMtime = filemtime($filename);
        usleep(1000 * 1500);

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $this->assertEquals($originalMtime, filemtime($filename), 'File was not correctly discarded.');

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST2");
        $file->persistOnClose();
        unset($file);

        $this->assertNotEquals($originalMtime, filemtime($filename), 'File was not correctly persisted.');

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST2123");
        $file->persistOnClose();
        unset($file);

        $this->assertNotEquals($originalMtime, filemtime($filename), 'File was not correctly persisted.');
    }

    public function testPersistIfNotChanged()
    {
        $filename = $this->tempnam();

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $originalMtime = filemtime($filename);
        usleep(1000 * 1500);

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose(AtomicTempFileObject::PERSIST_UNCHANGED);
        unset($file);

        $this->assertNotEquals($originalMtime, filemtime($filename), 'File was not correctly persisted.');

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST2");
        $file->persistOnClose();
        unset($file);

        $this->assertNotEquals($originalMtime, filemtime($filename), 'File was not correctly persisted.');
    }

    public function testDiscard()
    {
        $filename = $this->tempnam();

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST1");
        $file->persistOnClose();
        unset($file);

        $originalMtime = filemtime($filename);
        usleep(1000 * 1500);

        $file = new AtomicTempFileObject($filename);
        $file->fwrite("TEST2");
        $file->persistOnClose(AtomicTempFileObject::DISCARD);
        unset($file);

        $this->assertEquals($originalMtime, filemtime($filename), 'File was not correctly discarded.');
    }

    /**
     * @expectedException PHPUnit\Framework\Error\Warning
     */
    public function testPersistWarning()
    {
        $filename = $this->tempnam();

        $file = new AtomicTempFileObject($filename);
        $this->tempFiles[] = $file->getRealPath();
        $file->fwrite("TEST1");
        unset($file);
    }

    public function testOnPersist()
    {
        $filename = $this->tempnam();
        $check = [];
        $file = new AtomicTempFileObject($filename);
        $check[$file->getDestinationRealPath()] = true;
        $file->fwrite("TEST1");
        $file->persistOnClose();
        $file->onPersist(function ($file) use (&$check) {
            $check[$file->getDestinationRealPath()] = false;
        });
        unset($file);

        $this->assertEmpty(array_filter($check), 'On persist callback was not executed.');

        $check = [];
        $file = new AtomicTempFileObject($filename);
        $check[$file->getDestinationRealPath()] = true;
        $file->fwrite("TEST1");
        $file->persistOnClose();
        $file->onPersist(function ($file) use (&$check) {
            $check[$file->getDestinationRealPath()] = false;
        });
        unset($file);

        $this->assertNotEmpty(array_filter($check), 'On persist callback was executed.');
    }

    public function testOnDiscard()
    {
        $filename = $this->tempnam();
        $check = [];
        $file = new AtomicTempFileObject($filename);
        $check[$file->getDestinationRealPath()] = true;
        $file->fwrite("TEST1");
        $file->persistOnClose();
        $file->onDiscard(function ($file) use (&$check) {
            $check[$file->getDestinationRealPath()] = false;
        });
        unset($file);

        $this->assertNotEmpty(array_filter($check), 'On discard callback was not executed.');

        $check = [];
        $file = new AtomicTempFileObject($filename);
        $check[$file->getDestinationRealPath()] = true;
        $file->fwrite("TEST1");
        $file->persistOnClose(AtomicTempFileObject::DISCARD);
        $file->onDiscard(function ($file) use (&$check) {
            $check[$file->getDestinationRealPath()] = false;
        });
        unset($file);

        $this->assertEmpty(array_filter($check), 'On discard callback was executed.');
    }

    public function testProcess()
    {
        $txtFile = $this->fixturesPath . '/txtfile1-input';
        $txtFileObject = new \SplFileObject($txtFile);

        $dirname = $this->tempdirnam();

        $destFile = new AtomicTempFileObject($dirname . '/txtfile1-result');
        $destFile->process($txtFileObject, function ($line, $lineNum, $input, $output) use ($dirname) {
            $no = ($lineNum % 2);
            if ($no == 0) {
                $output->fwrite($line);
            }
        });
        $destFile->persistOnClose();
        unset($destFile);

        $this->assertTrue(file_exists($dirname . '/txtfile1-result'), 'File was not written.');

        $expected = file_get_contents($this->fixturesPath . '/txtfile1-result');
        $result = file_get_contents($dirname . '/txtfile1-result');
        $this->assertEquals($expected, $result, 'File was not processed correctly.');
    }

    public function testFilePutContents()
    {
        $filename = $this->tempnam();
        AtomicTempFileObject::file_put_contents($filename, "TEST1");

        $this->assertEquals(5, filesize($filename), 'File is not correctly persisted - size.');
        $this->assertEquals("TEST1", file_get_contents($filename), 'File is not correctly persisted - content.');

        $old_include_path = set_include_path(dirname($filename));
        AtomicTempFileObject::file_put_contents(basename($filename), "TEST2", FILE_USE_INCLUDE_PATH);
        set_include_path($old_include_path);

        $this->assertEquals(5, filesize($filename), 'File is not correctly persisted - size.');
        $this->assertEquals("TEST2", file_get_contents($filename), 'File is not correctly persisted - content.');
    }
}
