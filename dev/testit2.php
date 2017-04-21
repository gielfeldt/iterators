<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\ReplaceableIterator;
use Gielfeldt\Iterators\DebugIterator;

$i = new ArrayIterator([null, null]);
$i = new IteratorIterator($i);
var_dump(iterator_to_array($i)); // Seek to the end
print "Valid: " . $i->valid() . "\n";
$i->append('test');
print "Valid: " . $i->valid() . "\n";
print "Current: " . $i->current() . "\n";

$i = new ArrayIterator([null, null]);
var_dump(iterator_to_array($i)); // Seek to the end
print "Valid: " . $i->valid() . "\n";
$i->append('test');
print "Valid: " . $i->valid() . "\n";
print "Current: " . $i->current() . "\n";
exit;

$d = new DebugIterator($o);

#$i = new ReplaceableIterator($o);
$i = new ReplaceableIterator($d);

$i->rewind();
exit;

foreach ($i as $k => $v) {
    print "$k => $v\n";
}

var_dump($i->valid());
var_dump($i->current());

$o->append('test');
var_dump($i->valid());
var_dump($i->current());
