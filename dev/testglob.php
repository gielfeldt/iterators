<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\GlobIterator;

#$i = new GlobIterator('**/composer.json', GlobIterator::GLOB_NOSORT | \FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::KEY_AS_FILENAME);
#$i = new GlobIterator('**/composer.json', GlobIterator::GLOB_NOSORT | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME);
$i = new GlobIterator('**/*.php', \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME);
#$i = new GlobIterator('vendor/**composer.json', GlobIterator::GLOB_NOSORT | \FilesystemIterator::CURRENT_AS_PATHNAME);
#$i = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('vendor/', \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME));
foreach ($i as $k => $v) {
    print "$k => ";
    print is_scalar($v) ? $v : $v->getPathname();
    print "\n";
}
