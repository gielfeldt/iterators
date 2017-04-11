# Iterators

[![Build Status](https://travis-ci.org/gielfeldt/iterators.svg?branch=master)][1]
[![Test Coverage](https://codeclimate.com/github/gielfeldt/iterators/badges/coverage.svg)][2]
[![Code Climate](https://codeclimate.com/github/gielfeldt/iterators/badges/gpa.svg)][3]

[![Latest Stable Version](https://poser.pugx.org/gielfeldt/iterators/v/stable.svg)][4]
[![Latest Unstable Version](https://poser.pugx.org/gielfeldt/iterators/v/unstable.svg)][4]
[![Dependency Status](https://www.versioneye.com/user/projects/58ed39a526a5bb0038e422f7/badge.svg?style=flat)][5]
[![License](https://poser.pugx.org/gielfeldt/iterators/license.svg)][6]
[![Total Downloads](https://poser.pugx.org/gielfeldt/iterators/downloads.svg)][4]

[![Documentation Status](https://readthedocs.org/projects/iterators/badge/?version=stable)][7]
[![Documentation Status](https://readthedocs.org/projects/iterators/badge/?version=latest)][7]

## Installation

```
composer require gielfeldt/iterators
```

### Iterators

This library contains a bunch of various iterators that I made primarily for
getting more acquainted with iterators in PHP. They might be of use to someone.
So here you go.

#### ChecksumIterator

#### ClonedIterator /  / RecursiveClonedIterator

#### CombineIterator

#### CountableIterator

Takes any iterator and makes it countable, simply by iterating through it and counting.
```
<?php

use Gielfeldt\Iterators\DiffIterator;

$some_noncountable_iterator = new \IteratorIterator(new \ArrayIterator([1, 2, 3]));
$iterator = new CountableIterator($some_noncountable_iterator);
var_dump(count($iterator));
```

Output:
```
int(3)
```

#### DiffIterator
Compares two iterators. Similar to array_diff. Possible to set a custom compare
function.
```
<?php

use Gielfeldt\Iterators\DiffIterator;

$input1 = new \ArrayIterator(['key1'  => 'value1', 'key2'  => 'value2', 'key3'  => 'value3']);
$input2 = new \ArrayIterator(['key11' => 'value1', 'key22' => 'value1', 'key2'  => 'value3']);
$input3 = new \ArrayIterator(['key1'  => 'value2', 'key2'  => 'value2', 'key33' => 'value3']);

$iterator = new DiffIterator($input1, $input2, $input3);
$iterator->setDiff(function ($iterator, $key, $value) {
    return $iterator->key() == $key && $iterator->current() == $value;
});
var_dump(iterator_to_array($iterator));
```

Output:
```
array(2) {
  'key1' =>
  string(6) "value1"
  'key3' =>
  string(6) "value3"
}
```

#### FlipIterator
Similar to array_flip. However, iterators can have non-unique keys. Be aware of
this when using iterator_to_array();

```
<?php

use Gielfeldt\Iterators\FlipIterator;

$input = new \ArrayIterator([
    'key1'  => 'value1',
    'key2'  => 'value2',
    'key3'  => 'value3',
    'key4'  => 'value1',
    'key5'  => 'value2',
    'key6'  => 'value3',
]);

$iterator = new FlipIterator($input);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}
```

Output:
```
value1 => key1
value2 => key2
value3 => key3
value1 => key4
value2 => key5
value3 => key6

array(3) {
  'value1' =>
  string(4) "key4"
  'value2' =>
  string(4) "key5"
  'value3' =>
  string(4) "key6"
}
```

#### GlobIterator

#### IntersectIterator

#### KeysIterator

#### MapIterator /  / RecursiveMapIterator

#### RepeatIterator

#### SortIterator / RecursiveSortIterator

#### UniqueIterator  / RecursiveUniqueIterator

#### ValuesIterator

#### ZipIterator


### Not iterators as such ...

#### CsvFileObject

#### AtomicTempFileObject

#### AtomicTempFileObjects


### Helpers

#### Iterator


### Caveats

1. Lots probably.


[1]:  https://travis-ci.org/gielfeldt/iterators
[2]:  https://codeclimate.com/github/gielfeldt/iterators/coverage
[3]:  https://codeclimate.com/github/gielfeldt/iterators
[4]:  https://packagist.org/packages/gielfeldt/iterators
[5]:  https://www.versioneye.com/user/projects/58ed39a526a5bb0038e422f7
[6]:  https://github.com/gielfeldt/iterators/blob/master/LICENSE.md
[7]:  https://readthedocs.org/projects/iterators/?badge=latest
