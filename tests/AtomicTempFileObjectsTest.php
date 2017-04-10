<?php
namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\CsvFileObject;
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
}
