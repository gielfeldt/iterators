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
/*

$file = new \SplFileObject('mytestfile');
foreach (new ChunkIterator($file, 2) as $i => $lines) {
    AtomicTempFileObject::file_put_contents("mytestfile.$i", implode("", iterator_to_array($lines)));
}

exit;
/**/
/*
$i = new RepeatIterator(new \ArrayIterator(range(1, 65452)), 4.68);
var_dump(count($i));
var_dump(count($i));
foreach ($i as $k => $v) {
    #print "$k => $v\n";
}
var_dump(count($i));
exit;
*/


/*
$i = new RepeatIterator(new \ArrayIterator([0]), 20);
$i = new MapIterator($i, function ($i) {
    return [$i->getCurrentIteration(), rand(1, 100)];
});
#$i = new \LimitIterator($i, 0, 10);
foreach ($i as $k => $v) {
    print "$k => $v\n";
}
exit;
*/

$fibonacci = new EventIterator();
$fibonacci->attach('rewind', function ($iterator) {
    $iterator->setInnerIterator(new \ArrayIterator([2, 1]));
});
$fibonacci->attach('finished', function ($iterator, &$valid, $callback) {
    $newValue = Iterator::sum($iterator->getInnerIterator());
    if ($newValue < INF) {
        $iterator->getInnerIterator()->append($newValue);
        $iterator->getInnerIterator()->offsetUnset($iterator->getInnerIterator()->key() - 2);
        $iterator->getInnerIterator()->seek(1);
        $valid = true;
        return;
    }
    $valid = false;
});
print_r(iterator_to_array(new \LimitIterator($fibonacci, 0, 99999), false));
print_r(iterator_to_array(new \LimitIterator($fibonacci, 0, 99999), false));
exit;


$iterator = new EventIterator();
$iterator->onRewind(function ($iterator) {
    $iterator->setInnerIterator(new \ArrayIterator(range(1, 10)));
});

$iterator->onFinished(function ($iterator) {
    $iterator->onFinished(null);
    $iterator->setInnerIterator(new \ArrayIterator(range(11, 20)));
    return true;
});

foreach ($iterator as $k => $v) {
    print "$k => $v\n";
}

exit;

$fibonacci = function ($x, $y) {
    $i = 0;
    yield $i++ => $x;
    yield $i++ => $y;
    do {
        $z = $x + $y;
        $x = $y;
        $y = $z;
        yield $i++ => $z;
    } while ($z !== INF);
};

foreach ($fibonacci(2, 1) as $k => $v) {
    print "$k => $v\n";
    #if ($v > 10000) break;
}

exit;
$e = new EventIterator();
#$e = new DebugIterator($e);
#$e->push(0);
/**/
$e->onRewind(function ($e) {
    print "ON REWIND!\n";
    $i = new \SplDoublyLinkedList();
    $i->push(0);
    $i->push(1);
    $e->setInnerIterator($i);
});
/**/
$e->onNext(function ($e) {
    $pos = $e->key();
    if ($pos > 0) {
        print "POS: $pos\n";
        $e->getInnerIterator()->push(
            $e->getInnerIterator()->offsetGet($pos - 1) +
            $e->getInnerIterator()->offsetGet($pos)
        );
        #$e->getInnerIterator()->offsetUnset($pos - 1);
        #var_dump($pos);
        return;
    }
});
/**/
foreach ($e as $k => $v) {
    print "$k => $v\n";
    if ($v > 10000) break;
}

var_dump(iterator_to_array($e->getInnerIterator()));

exit;
$e = new EventIterator();
$e->setInnerIterator(new \ArrayIterator([rand(1,100)]));
$e->onFinished(function ($e) {
    $e->setInnerIterator(new \ArrayIterator([rand(1,100)]));
    return true;
});

foreach ($e as $k => $v) {
    print "$k => $v\n";
}

exit;

$a1 = new ArrayIterator(range(1, 10));
$a2 = new ArrayIterator(range(11, 20));
$a3 = new ArrayIterator(range(21, 30));
$s = new SplQueue();
$s->enqueue($a1);
$s->enqueue($a2);
$s->enqueue($a3);

$i = new \AppendIterator();
$e = new EventIterator($i);
$e->append($s->dequeue());
$e->onFinished(function ($eventIterator) use ($s) {
    $next = !$s->isEmpty() ? $s->dequeue() : null;
    if ($next) {
        $eventIterator->append($next);
        return true;
    }
    return false;
});

foreach ($e as $k => $v) {
    print "$k => $v\n";
}

exit;

$a = new ArrayIterator(range(1, 18));
$d = new DebugIterator($a);

var_dump($d->count());
exit;
foreach ($d as $k => $v) {
    print "$k => $v\n";
}
exit;

$o = new ChunkIterator($a, 4);
$o = new RepeatIterator($o, 2);
foreach ($o as $ok => $ov) {
    print "OUTER: $ok\n";
    #$ov = iterator_to_array($ov);
    #$ov = new RepeatIterator(new \ArrayIterator($ov), 2);
    #$ov = new RepeatIterator($ov, 2);
    foreach ($ov as $ik => $iv) {
        print "-- INNER: $ik => $iv\n";
        #if ($iv == 6) break;
    }
}

exit;

/*
$r = new ReplaceableIterator($a);
var_dump($a[4]);
var_dump($r[4]);
exit;
$l = new \LimitIterator($r, 10, 4);
foreach ($l as $v) {
    print "-- $v\n";
}
exit;
*/

$o = new ArrayIterator();
#$i = new ReplaceableIterator($a);
#$i = new NoRewindIterator($i);

#$nr = new NoRewindIterator(new ReplaceableIterator($a));
$nr = new NoRewindIterator($a);
$i = new \LimitIterator($nr, 0, 4);
$i = new EventIterator($i);

$i->onFinished(function ($iterator, $callback) use ($o, $nr) {
    $i = new \LimitIterator($nr, 0, 4);
    $i->rewind();
    $valid = $i->valid();
    $i = new EventIterator($i);
    if ($valid) {
        $i->onFinished($callback);
        $o->append($i);
    }
    #$iterator->rewind();
    #$a2->rewind();
    #$a2->current();
    return false;
});
$o->append($i);
/*
$e = new EventIterator($i);
$e->onValid(function ($iterator) use ($a2) {
    print "onValid!\n";
    $iterator->getInnerIterator()->append($a2);
    #$iterator->rewind();
    #$a2->rewind();
    #$a2->current();
    return true;
});
*/
foreach ($o as $ok => $ov) {
    foreach ($ov as $ik => $iv) {
        print "-- $iv\n";
    }
}

exit;



$a1 = new ArrayIterator(range(1, 10));
$a2 = new ArrayIterator(range(11, 20));

$i = new \AppendIterator();
$i->append($a1);
#$i = new ReplaceableIterator($a1);
#$e = new EventIterator(new DebugIterator($i));
$e = new EventIterator($i);
$e->onFinished(function ($iterator) use ($a2) {
    #var_dump($iterator);
    print "HERE!\n";
    $iterator->getInnerIterator()->append($a2);
    #$iterator->rewind();
    #$a2->rewind();
    #$a2->current();
    return true;
});

$l = new \LimitIterator($e, 0, 13);
foreach ($l as $v) {
    print "-- $v\n";
}
/*
$i->setInnerIterator($a2);
foreach ($i as $v) {
    print "$v\n";
}
*/
