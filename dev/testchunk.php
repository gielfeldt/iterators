<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\Iterator;
use Gielfeldt\Iterators\ReplaceableIterator;
use Gielfeldt\Iterators\EventIterator;
use Gielfeldt\Iterators\DebugIterator;
use Gielfeldt\Iterators\ChunkIterator;
use Gielfeldt\Iterators\RepeatIterator;
use Gielfeldt\Iterators\MapIterator;
use Gielfeldt\Iterators\AtomicTempFileObject;

$a = new ArrayIterator(range(1, 18));

$o = new ChunkIterator($a, 4);
$o->rewind();
print_r(iterator_to_array($o->current()));
$o->next();
print_r(iterator_to_array($o->current()));
exit;

foreach ($o as $ok => $ov) {
    print "OUTER: $ok\n";
    #var_dump($ov);
    foreach ($ov as $ik => $iv) {
        print "-- INNER: $ik => $iv\n";
        if ($iv == 6) break;
    }
}
print "-----------\n";
foreach ($o as $ok => $ov) {
    print "OUTER: $ok\n";
    foreach ($ov as $ik => $iv) {
        print "-- INNER: $ik => $iv\n";
        if ($iv == 6) break;
    }
}

exit;
