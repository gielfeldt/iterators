<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\CachingIterator;

class CachingIteratorTest extends IteratorsTestBase
{
    public function testCachingIteratorCloned()
    {
        $object = (object) ['test6' => 'test7'];
        $input = [
            'test1',
            'test2' => 'test3',
            ['test4' => 'test5'],
            $object,
        ];

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $inputResult = iterator_to_array($inputIterator);
        $cachedResult = iterator_to_array($cachingIterator);
        $this->assertEquals($inputResult, $cachedResult, 'Iterator not cached properly.');

        $inputIterator[0] = 'test1org';
        $inputResult = iterator_to_array($inputIterator);
        $cachedResult = iterator_to_array($cachingIterator);
        $this->assertEquals('test1org', $inputResult[0], 'Iterator not cached properly.');
        $this->assertEquals('test1', $cachedResult[0], 'Iterator not cached properly.');

        $object->test6 = 'test8';
        $this->assertEquals($object, $inputResult[2], 'Iterator not cached properly.');
        $this->assertNotEquals($object, $cachedResult[2], 'Iterator not cached properly.');
    }

    public function testCachingIteratorUncloned()
    {
        $object = (object) ['test6' => 'test7'];
        $input = [
            'test1',
            'test2' => 'test3',
            ['test4' => 'test5'],
            $object,
        ];

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator, 0);
        $inputResult = iterator_to_array($inputIterator);
        $cachedResult = iterator_to_array($cachingIterator);
        $this->assertEquals($inputResult, $cachedResult, 'Iterator not cached properly.');

        $inputIterator[0] = 'test1org';
        $inputResult = iterator_to_array($inputIterator);
        $cachedResult = iterator_to_array($cachingIterator);
        $this->assertEquals('test1org', $inputResult[0], 'Iterator not cached properly.');
        $this->assertEquals('test1', $cachedResult[0], 'Iterator not cached properly.');

        $object->test6 = 'test8';
        $this->assertEquals($object, $inputResult[2], 'Iterator not cached properly.');
        $this->assertEquals($object, $cachedResult[2], 'Iterator not cached properly.');
    }

    public function testCachingIteratorCount()
    {
        $input = range(1, 20);
        $inputIterator = new \ArrayIterator($input);

        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertCount(20, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator($inputIterator);
        $cachingIterator->seek(4);
        $this->assertCount(20, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator($inputIterator);
        $cachingIterator[] = 'test';
        $this->assertCount(21, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator($inputIterator);
        $cachingIterator->seek(4);
        $cachingIterator[] = 'test';
        $this->assertCount(21, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator(new \IteratorIterator($inputIterator));
        $this->assertCount(20, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator(new \IteratorIterator($inputIterator));
        $cachingIterator->seek(4);
        $this->assertCount(20, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator(new \IteratorIterator($inputIterator));
        $cachingIterator[] = 'test';
        $this->assertCount(21, $cachingIterator, 'Iterator has incorrect count.');

        $cachingIterator = new CachingIterator(new \IteratorIterator($inputIterator));
        $cachingIterator->seek(4);
        $cachingIterator[] = 'test';
        $this->assertCount(21, $cachingIterator, 'Iterator has incorrect count.');

    }

    public function testCachingIteratorSeek()
    {
        $input = range(1, 20);
        $inputIterator = new \ArrayIterator($input);

        $cachingIterator = new CachingIterator($inputIterator);
        $cachingIterator->rewind();
        $cachingIterator->seek(4);
        $this->assertEquals(5, $cachingIterator->current(), 'Iterator did not seek right.');

        $cachingIterator = new CachingIterator(new \IteratorIterator($inputIterator));
        $cachingIterator->rewind();
        $cachingIterator->seek(4);
        $this->assertEquals(5, $cachingIterator->current(), 'Iterator did not seek right.');
    }

    public function testCachingIteratorArrayAccess()
    {
        $input = range(1, 20);
        $input['test1'] = 'test2';
        $input['test3'] = 'test4';

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        iterator_to_array($cachingIterator);

        unset($inputIterator['test1']);
        $this->assertEquals(5, $inputIterator[4], 'Iterator did not access right.');
        $this->assertEquals(12, $inputIterator[11], 'Iterator did not access right.');
        $this->assertEquals('test4', $inputIterator['test3'], 'Iterator did not access right.');
        $this->assertFalse($inputIterator->offsetExists('test1'), 'Iterator did not access right.');

        unset($cachingIterator['test3']);
        $this->assertEquals(5, $cachingIterator[4], 'Iterator did not access right.');
        $this->assertEquals(12, $cachingIterator[11], 'Iterator did not access right.');
        $this->assertEquals('test2', $cachingIterator['test1'], 'Iterator did not access right.');
        $this->assertFalse($cachingIterator->offsetExists('test3'), 'Iterator did not access right.');
    }

    public function testCachingIteratorDelegation()
    {
        $input = range(1, 20);
        $input['test1'] = 'test2';
        shuffle($input);

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');
        
        $inputIterator->asort();
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->asort();
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator(array_flip($input));
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputIterator->ksort();
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->ksort();
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputIterator->natsort();
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->natsort();
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputIterator->natcasesort();
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->natcasesort();
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputIterator->uasort(function ($a, $b) { return $a <=> $b; });
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->uasort(function ($a, $b) { return $a <=> $b; });
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator(array_flip($input));
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputIterator->uksort(function ($a, $b) { return $a <=> $b; });
        $this->assertNotEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');
        $cachingIterator->uksort(function ($a, $b) { return $a <=> $b; });
        $this->assertEquals(iterator_to_array($inputIterator, false), iterator_to_array($cachingIterator, false), 'Iterator not cached properly.');

        $inputIterator = new \ArrayIterator($input);
        $cachingIterator = new CachingIterator($inputIterator);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

        $inputSerialized = $inputIterator->serialize();
        $cachingSerialized = $cachingIterator->serialize();

        $this->assertEquals($inputSerialized, $cachingSerialized, 'Iterator not serialized properly.');

        $newInput = new \ArrayIterator();
        $newCache = new CachingIterator(new \EmptyIterator());

        $newInput->unserialize($inputSerialized);
        $newCache->unserialize($cachingSerialized);
        $this->assertEquals(iterator_to_array($inputIterator), iterator_to_array($cachingIterator), 'Iterator not cached properly.');

    }

}
