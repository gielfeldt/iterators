<?php

namespace Gielfeldt\Tests\Iterators;

use org\bovigo\vfs\vfsStream;
use Gielfeldt\Iterators\GlobIterator;
use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\KeysIterator;
use Gielfeldt\Iterators\ValuesIterator;

class GlobIteratorTest extends IteratorsTestBase
{
    public function setup()
    {
        parent::setup();
        $this->setupVfs();
    }

    protected function setupVfs()
    {
        $structure = [
            'tmp' => [
                'somelog.log' => 'testst',
            ],
            'file1.txt' => 'xcvxcv',
            'file2.txt' => '',
            'dir1' => [
                'file1.txt' => 'sdfsdf',
                'file2.txt' => '',
                'dir2' => [
                    'file1.txt' => '',
                    'file2.txt' => '',
                ],
            ],
            'dir3' => [
                'file1.txt' => 'sdfsdf',
                'file2.txt' => '',
                'dir4' => [
                    'file1.txt' => '',
                    'file2.txt' => '',
                ],
            ],

        ];
        vfsStream::setup('test', 0775, $structure);
    }

    public function dataProvider()
    {
        return [
            $this->dataSet1(),
        ];
    }

    public function dataProviderSorted()
    {
        $datasets = $this->dataProvider();
        foreach ($datasets as &$dataset) {
            sort($dataset[1]);
        }
        return $datasets;
    }

    public function dataProviderGlob()
    {
        $datasets = $this->dataProvider();
        foreach ($datasets as &$dataset) {
            $dataset[0] = str_replace('vfs://test/', 'glob://', $dataset[0]);
            $dataset[1] = str_replace('vfs://test/', '', $dataset[1]);
        }
        return $datasets;
    }

    public function testGlobIteratorPath()
    {
        $iterator = new GlobIterator('vfs://test/dir1/*/*/');
        $this->assertEquals('vfs://test/dir1/', $iterator->getPath());

        $iterator = new GlobIterator('vfs://test/dir1/dir2*/*/');
        $this->assertEquals('vfs://test/dir1/', $iterator->getPath());

        $iterator = new GlobIterator('vfs://test/dir1/dir2/**/');
        $this->assertEquals('vfs://test/dir1/dir2/', $iterator->getPath());

        $iterator = new GlobIterator('vfs://test/dir1/dir2**/');
        $this->assertEquals('vfs://test/dir1/', $iterator->getPath());
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testGlobIterator($pattern, $expected)
    {
        $iterator = new GlobIterator($pattern);

        $result2 = new MapIterator($iterator, function($iterator) {
            return $iterator->current()->getPathname();
        });

        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertEquals($expected, iterator_to_array($result2), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');
    }

    /**
     * @dataProvider dataProviderSorted()
     */
    public function testGlobIteratorSorted($pattern, $expected)
    {
        $iterator = new GlobIterator($pattern, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO);

        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testGlobIteratorPathFilename($pattern, $expected)
    {
        $iterator = new GlobIterator($pattern, GlobIterator::GLOB_NOSORT | \FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::CURRENT_AS_PATHNAME);

        $expected2 = array_map('basename', $expected);
        $this->assertEquals($expected, iterator_to_array(new ValuesIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertEquals($expected2, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
    }

    public function testGlobIteratorCwd()
    {

        $root = $this->tempdirnam();
        mkdir($root);
        mkdir("$root/subdir1");
        mkdir("$root/subdir2");
        touch("$root/subdir1/file1.ext1");
        touch("$root/subdir1/file2.ext2");
        touch("$root/subdir2/file1.ext2");
        touch("$root/subdir2/file2.ext1");

        $cwd = getcwd();
        chdir($root);

        $expected = [
            'subdir1/file2.ext2',
            'subdir2/file1.ext2',
        ];

        $iterator = new GlobIterator("**.ext2");
        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');

        $expected = [
            'subdir1/file1.ext1',
            'subdir2/file2.ext1',
        ];

        $iterator = new GlobIterator("**.ext1");
        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');

        $expected = [
            './subdir1/file1.ext1',
            './subdir2/file2.ext1',
        ];

        $iterator = new GlobIterator("./**.ext1");
        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');

        $expected = [
            $root . '/subdir1/file1.ext1',
            $root . '/subdir2/file2.ext1',
        ];

        $iterator = new GlobIterator("$root/**.ext1");
        $this->assertEquals($expected, iterator_to_array(new KeysIterator($iterator)), 'GlobIterator did not return expected result.');
        $this->assertCount(count($expected), $iterator, 'GlobIterator did not return expected result.');
    }

    /**
     * @dataProvider dataProviderGlob()
     */
    public function testStreamWrapper($pattern, $expected)
    {
        GlobIterator::registerStreamWrapper('vfs://test/');

        $iterator = new \DirectoryIterator($pattern);
        $iterator = new MapIterator($iterator, function ($iterator) {
            return $iterator->current()->getFilename();
        });
        $this->assertEquals($expected, iterator_to_array($iterator), 'GlobIterator did not return expected result.');

        GlobIterator::unregisterStreamWrapper();
    }

    protected function dataSet1()
    {
        $pattern = 'vfs://test/**.txt';
        $expected = [
            'vfs://test/file1.txt',
            'vfs://test/file2.txt',
            'vfs://test/dir1/file1.txt',
            'vfs://test/dir1/file2.txt',
            'vfs://test/dir1/dir2/file1.txt',
            'vfs://test/dir1/dir2/file2.txt',
            'vfs://test/dir3/file1.txt',
            'vfs://test/dir3/file2.txt',
            'vfs://test/dir3/dir4/file1.txt',
            'vfs://test/dir3/dir4/file2.txt',
        ];
        return [$pattern, $expected];
    }
}
