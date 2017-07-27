<?php

require './vendor/autoload.php';

use Gielfeldt\Iterators\GlobIterator;

GlobIterator::registerStreamWrapper();

$s = microtime(true);
$m1 = memory_get_usage(1); $m2 = memory_get_usage(0);
print "$m1 $m2\n";
$i = new \GlobIterator('/tmp/files/*/*1000.txt');


$cnt = 0;
$m1 = memory_get_usage(1); $m2 = memory_get_usage(0);
print "$m1 $m2\n";
foreach ($i as $k => $v) {
    if ($cnt++ > 10) continue;
    $m1 = memory_get_usage(1); $m2 = memory_get_usage(0);
    print "$v $m1 $m2\n";
}
$e = microtime(true);

printf("took: %.08f seconds\n", $e - $s);

