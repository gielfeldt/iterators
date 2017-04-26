<?php
namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\CsvFileObject;
use Gielfeldt\Iterators\AtomicTempFileObject;
use Gielfeldt\Iterators\AtomicTempFileObjects;

class AtomicTempFileObjectsTest extends IteratorsTestBase
{
    public function testSplitCsvFile()
    {
        $csvFile = $this->fixturesPath . '/csvfile4-input';
        $csvFileObject = new CsvFileObject($csvFile);

        $dirname = $this->tempdirnam();

        $destFiles = new AtomicTempFileObjects();
        $destFiles->splitCsvFile($csvFileObject, function ($row) use ($dirname) {
            return $dirname . '/csvfile4-result-' . $row['no'];
        });
        $destFiles->persistOnClose();
        unset($destFiles);

        $this->assertTrue(file_exists($dirname . '/csvfile4-result-1'), 'File was not split correctly.');
        $this->assertTrue(file_exists($dirname . '/csvfile4-result-2'), 'File was not split correctly.');
        $this->assertTrue(file_exists($dirname . '/csvfile4-result-3'), 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-1');
        $result = file_get_contents($dirname . '/csvfile4-result-1');
        $this->assertEquals($expected, $result, 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-2');
        $result = file_get_contents($dirname . '/csvfile4-result-2');
        $this->assertEquals($expected, $result, 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-3');
        $result = file_get_contents($dirname . '/csvfile4-result-3');
        $this->assertEquals($expected, $result, 'File was not split correctly.');
    }

    public function testSplitCsvFileWithModification()
    {
        $csvFile = $this->fixturesPath . '/csvfile4-input';
        $csvFileObject = new CsvFileObject($csvFile);

        $dirname = $this->tempdirnam();

        $destFiles = new AtomicTempFileObjects();
        $destFiles->splitCsvFile($csvFileObject, function (&$row) use ($dirname) {
            $row['no'] = 'modified-' . $row['no'];
            $row['test'] = $row['value'];
            return $dirname . '/csvfile4-result-' . $row['no'];
        });
        $destFiles->persistOnClose();
        unset($destFiles);

        $this->assertTrue(file_exists($dirname . '/csvfile4-result-modified-1'), 'File was not split correctly.');
        $this->assertTrue(file_exists($dirname . '/csvfile4-result-modified-2'), 'File was not split correctly.');
        $this->assertTrue(file_exists($dirname . '/csvfile4-result-modified-3'), 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-modified-1');
        $result = file_get_contents($dirname . '/csvfile4-result-modified-1');
        $this->assertEquals($expected, $result, 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-modified-2');
        $result = file_get_contents($dirname . '/csvfile4-result-modified-2');
        $this->assertEquals($expected, $result, 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile4-result-modified-3');
        $result = file_get_contents($dirname . '/csvfile4-result-modified-3');
        $this->assertEquals($expected, $result, 'File was not split correctly.');
    }

    public function testProcess()
    {
        $csvFile = $this->fixturesPath . '/csvfile5-input';
        $csvFileObject = new \SplFileObject($csvFile);

        $dirname = $this->tempdirnam();

        $destFiles = new AtomicTempFileObjects();
        $destFiles->process($csvFileObject, function ($line, $lineNum, $input, $output) use ($dirname) {
            $no = ($lineNum % 3) + 1;
            if ($no == 3 || empty($line)) {
                return;
            }
            $fileName = $dirname . '/csvfile5-result-' . $no;
            $file = $output->isFileOpen($fileName) ? $output->getFile($fileName) : $output->openFile($fileName);
            $file->fwrite(trim($line) . ",test\n");
        });
        $destFiles->persistOnClose();
        unset($destFiles);

        $this->assertTrue(file_exists($dirname . '/csvfile5-result-1'), 'File was not split correctly.');
        $this->assertTrue(file_exists($dirname . '/csvfile5-result-2'), 'File was not split correctly.');
        $this->assertFalse(file_exists($dirname . '/csvfile5-result-3'), 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile5-result-1');
        $result = file_get_contents($dirname . '/csvfile5-result-1');
        $this->assertEquals($expected, $result, 'File was not split correctly.');

        $expected = file_get_contents($this->fixturesPath . '/csvfile5-result-2');
        $result = file_get_contents($dirname . '/csvfile5-result-2');
        $this->assertEquals($expected, $result, 'File was not split correctly.');
    }

    public function testOpenFile()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $newFile = $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->assertInstanceOf(AtomicTempFileObject::class, $newFile, 'Temp file not properly opened.');

        $filename = $this->tempnam();
        $anotherNewFile = $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->assertInstanceOf(AtomicTempFileObject::class, $anotherNewFile, 'Temp file not properly opened.');
    }

    public function testOpenFileException()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $newFile = $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->assertInstanceOf(AtomicTempFileObject::class, $newFile, 'Temp file not properly opened.');

        $this->expectException(\RuntimeException::class);
        $sameNewFile = $destFiles->openFile($filename);
    }

    public function testIsFileOpen()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $newFile = $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->assertTrue($destFiles->isFileOpen($filename), 'Temp file not properly opened.');
        $this->assertFalse($destFiles->isFileOpen($filename . '.notopened'), 'Temp file open when it should not be?');
    }

    public function testAddFile()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename1 = $this->tempnam();
        $destFiles->addFile(new AtomicTempFileObject($filename1));
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $filename2 = $this->tempnam();
        $destFiles->addFile(new AtomicTempFileObject($filename2));
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        unset($destFiles);
        $this->assertFalse(file_exists($filename1), 'File was not discarded like it should.');
        $this->assertFalse(file_exists($filename2), 'File was not discarded like it should.');
    }

    public function testAddFileException()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $file = new AtomicTempFileObject($filename);

        $destFiles->addFile($file);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->expectException(\RuntimeException::class);
        $destFiles->addFile($file);
    }

    public function testGetFile()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $file = $destFiles->getFile($filename);
        $this->assertInstanceOf(AtomicTempFileObject::class, $file, 'Temp file not properly opened.');
        $this->assertEquals($filename, $file->getDestinationRealPath());
    }

    public function testGetFileException()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename = $this->tempnam();
        $destFiles->openFile($filename);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $this->expectException(\RuntimeException::class);
        $file = $destFiles->getFile($filename . '.notopened');
    }

    public function testGetFiles()
    {
        $destFiles = new AtomicTempFileObjects();

        $filename1 = $this->tempnam();
        $destFiles->openFile($filename1);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $filename2 = $this->tempnam();
        $destFiles->openFile($filename2);
        $destFiles->persistOnClose(AtomicTempFileObject::DISCARD);

        $files = $destFiles->getFiles();
        $this->assertEquals([$filename1, $filename2], array_keys($files), 'Temp files not properly opened.');
    }
}
