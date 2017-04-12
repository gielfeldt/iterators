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

[create an anchor](#checksumiterator)
[create an anchor](#zipiterator)

####checksumiterator ChecksumIterator
Generate a checksum for an iterator, either per iteration or the entire dataset.

```php
use Gielfeldt\Iterators\ChecksumIterator;

$input = new \ArrayIterator([
    ['key1' => 'value1'],
    ['key2' => 'value2'],
    ['key3' => 'value3'],
]);

$iterator = new ChecksumIterator($input, 'md5');
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

var_dump($iterator->getChecksum());

var_dump((string) $iterator);
```

Output:
```
0 => e2e517365ffe6fedd279364e3fa74786
1 => f0a0db0fc9abe193b21fd657fe678884
2 => c04f606bb5bba82282dfa93edb59c6ee

string(32) "4fd19adc845da6fdd9c7c394f4626bac"

string(32) "4fd19adc845da6fdd9c7c394f4626bac"
```

#### ClonedIterator / RecursiveClonedIterator

#### CombineIterator
Similar to array_combine(). However, iterators can have non-unique keys. Be aware of
this when using iterator_to_array();

```php
use Gielfeldt\Iterators\CombineIterator;

$keys = new \ArrayIterator(['key1', 'key2', 'key3', 'key1', 'key2', 'key3']);
$values = new \ArrayIterator(['value1', 'value2', 'value3', 'value4', 'value5', 'value6']);

$iterator = new CombineIterator($keys, $values);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

var_dump(iterator_to_array($iterator));
```

Output:
```
key1 => value1
key2 => value2
key3 => value3
key1 => value4
key2 => value5
key3 => value6

array(3) {
  'key1' =>
  string(6) "value4"
  'key2' =>
  string(6) "value5"
  'key3' =>
  string(6) "value6"
}
```

#### CountableIterator

Takes any iterator and makes it countable, simply by iterating through it and counting.
```php
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
Compares two iterators. Similar to array_diff(). Possible to set a custom compare
function.
```php
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
Similar to array_flip(). However, iterators can have non-unique keys. Be aware of
this when using iterator_to_array();

```php
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

var_dump(iterator_to_array($iterator));
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
Similar to array_keys().

```php
use Gielfeldt\Iterators\KeysIterator;

$input = new \ArrayIterator([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
]);

$iterator = new KeysIterator($input);

var_dump(iterator_to_array($iterator));
```

Output:
```
array(3) {
  [0] =>
  string(4) "key1"
  [1] =>
  string(4) "key2"
  [2] =>
  string(4) "key3"
}
```

#### MapIterator / RecursiveMapIterator

#### RepeatIterator

#### SortIterator / RecursiveSortIterator

#### UniqueIterator  / RecursiveUniqueIterator

#### ValuesIterator
Similar to array_vales().

```php
use Gielfeldt\Iterators\ValuesIterator;

$input = new \ArrayIterator([
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
]);

$iterator = new ValuesIterator($input);

var_dump(iterator_to_array($iterator));
```

Output:
```
array(3) {
  [0] =>
  string(4) "value1"
  [1] =>
  string(4) "value2"
  [2] =>
  string(4) "value3"
}
```

####zipiterator ZipIterator
"zip" multiple iterators together.

```php
use Gielfeldt\Iterators\ZipIterator;

$input1 = new \ArrayIterator([
    'key1' => 'value11',
    'key2' => 'value12',
    'key3' => 'value13',
]);

$input2 = new \ArrayIterator([
    'key21' => 'value21',
    'key22' => 'value22',
    'key23' => 'value23',
]);

$input3 = new \ArrayIterator([
    'key1' => 'value31',
    'key2' => 'value32',
    'key3' => 'value33',
]);

$iterator = new ZipIterator($input1, $input2, $input3);
foreach ($iterator as $key => $value) {
    print "$key => $value\n";
}

var_dump(iterator_to_array($iterator));
```

Output:
```
key1 => value11
key21 => value21
key1 => value31
key2 => value12
key22 => value22
key2 => value32
key3 => value13
key23 => value23
key3 => value33

array(6) {
  'key1' =>
  string(7) "value31"
  'key21' =>
  string(7) "value21"
  'key2' =>
  string(7) "value32"
  'key22' =>
  string(7) "value22"
  'key3' =>
  string(7) "value33"
  'key23' =>
  string(7) "value23"
}
```

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
