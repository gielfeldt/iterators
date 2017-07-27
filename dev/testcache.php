<?php

require 'vendor/autoload.php';

use Gielfeldt\Iterators\CachingIterator;
use Gielfeldt\Iterators\ShuffleIterator;

$s = new \SplDoublyLinkedList();
$s->add('sasd', 'asdasd');
#var_dump($s[0]);
#unset($s[0]);
exit;

$i = new ArrayIterator(range(1, 20));
#$i = new ShuffleIterator($i);
#$i = new \IteratorIterator($i);
$c = new CachingIterator($i);

/*
$s = $c;
$serialized = $s->serialize();
var_dump($serialized);
$s->unserialize($serialized);
print_r(iterator_to_array($s));
#var_dump(unserialize($c->serialize()));
exit;

/*
foreach ($c as $k => $v) {
    print "$k => $v ($c->finished)\n";
    if ($k > 10) break;
}
#print "FINISHED: $c->finished\n";
foreach ($c as $k => $v) {
    print "$k => $v ($c->finished)\n";
}
print "FINISHED: $c->finished\n";
exit;
foreach ($c as $k => $v) {
    print "$k => $v\n";
    if ($k > 10) break;
}
print "FINISHED: $c->finished\n";

exit;
*/
#$c->seek(3);
#var_dump($c->current());
#unset($c[3]);
#var_dump($c->current());
#$c->rewind();
#$c = $i;
$c->rewind();
dump($c);
$c->next();
dump($c);
$c->next();
dump($c);
$c->next();
dump($c);
$c->seek(4);
var_dump($c[1]);
dump($c);
$c->next();
dump($c);
$c->next();
dump($c);
$c->prev();
dump($c);
#$c->seek();
exit;

function dump($i) {
    print $i->getIndex() . ': ' . $i->key() . ' => ' . $i->current() . "\n";
}

$c['test'] = 'hello';
$c->seek(20);
var_dump($c->current());
var_dump(count($c));
var_dump(count($c));
var_dump($c[4]);
var_dump($c['test']);
#var_dump($c);
