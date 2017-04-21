<?php

require 'vendor/autoload.php';

use org\bovigo\vfs\vfsStream;
use Gielfeldt\Iterators\GlobIterator;

$structure = [
    'tmp' => [
        'somelog.log' => 'testst',
        'subdir' => [
            'anotherlog.log',
        ],
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

#var_dump(stream_get_wrappers());
#exit;

vfsStream::setup('root', 0775, $structure);
GlobIterator::registerStreamWrapper('vfs://root');
#GlobIterator::registerStreamWrapper();
#stream_wrapper_unregister('glob');
#stream_wrapper_register('glob', TestGlob::class, STREAM_IS_URL);

/*
$dh = opendir('glob:///tmp/*.log');
while ($file = readdir($dh)) {
    var_dump($file);
}
exit;
*/

/**/
#$i = new RecursiveDirectoryIterator('glob:///tmp/**.log');
#$i = new RecursiveDirectoryIterator('glob://*./file1.txt');
#$r = new RecursiveTreeIterator($i);
#$r = new RecursiveIteratorIterator($i);
#$r = new GlobIterator('vfs://root//tmp/*.log');
/**/
$r = new DirectoryIterator("glob:///tmp/**.log");
#$r = new FilesystemIterator("glob:///tmp/**.log", FilesystemIterator::CURRENT_AS_SELF);
#$r = new GlobIterator('vfs://root/**.txt');
#$r = new GlobIterator('/tmp/**.log', \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF);
#$r = new GlobIterator('/tmp/**.log');
#$r = new GlobIterator('./**composer.json');
print "Found:" . count($r) . " files\n";
#exit;
foreach ($r as $k => $v) {
    var_dump($v);
    #print "$k => $v\n";
    #print
}

var_dump(file_get_contents('vfs://root/dir1/file1.txt'));
#var_dump(stat('vfs://root/file2.txt'));
