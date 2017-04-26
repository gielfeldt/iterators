<?php

namespace Gielfeldt\Tests\Iterators;

use Gielfeldt\Iterators\ChecksumIterator;

class ChecksumIteratorTest extends IteratorsTestBase
{
    public function testChecksumIteratorSha()
    {
        $input = new \ArrayIterator(['test1', 'test2', ['test3' => 'test4']]);
        $expectedSha256 = [
            '1b4f0e9851971998e732078544c96b36c3d01cedf7caa332359d6f1d83567014',
            '60303ae22b998861bce3b28f33eec1be758a213c86c93c076dbe9f558c11c752',
            '1d333fb110a14570eb79b4335bff409d8387cd56f64440b43d1bcfed19ec26d5',
        ];
        $expectedSha256Full = 'af44c62aacd3d25cc2bd26e773e8f6445d287972539b4e08d93e7fb6b986c774';

        $iterator = new ChecksumIterator($input);

        $this->assertEquals($expectedSha256, iterator_to_array($iterator), 'Checksum was incorrect');
        $this->assertEquals($expectedSha256Full, (string) ($iterator), 'Checksum was incorrect');
    }

    public function testChecksumIteratorMd5()
    {
        $input = new \ArrayIterator(['test1', 'test2', ['test3' => 'test4']]);
        $expectedSha256 = [
            '5a105e8b9d40e1329780d62ea2265d8a',
            'ad0234829205b9033196ba818f7a872b',
            '75f0927dd88631f761f52366c47598e5',
        ];
        $expectedSha256Full = '755ab2a5fabe521db787985490cb709b';

        $iterator = new ChecksumIterator($input, 'md5');

        $this->assertEquals($expectedSha256, iterator_to_array($iterator), 'Checksum was incorrect');
        $this->assertEquals($expectedSha256Full, (string) ($iterator), 'Checksum was incorrect');
    }

    public function testChecksumIteratorSerializer()
    {
        $input = new \ArrayIterator(['test1', 'test2', 'test3']);
        $expectedSha256 = [
            '61e72af42299c08c61a7c45ba683a73bf98b13e84a955bc6bd9be8b6d8231475',
            '6150fd24ef47ab6afe28f5b5d4743ea98441edbd02f9c42191a22eee3b29c33e',
            'a06a8f1b0d96ac38f5b12625d3e3387a81b2a1810a7bb3b2170f992f47d72e73',
        ];
        $expectedSha256Full = 'f1295199354dcd70d114b71c12f3d103a720c7cedaa953c1f808c3c637452489';

        $iterator = new ChecksumIterator($input);
        $iterator->setSerializer(function ($iterator) {
            return $iterator->key() . $iterator->current();
        });

        $this->assertEquals($expectedSha256, iterator_to_array($iterator), 'Checksum was incorrect');
        $this->assertEquals($expectedSha256Full, (string) ($iterator), 'Checksum was incorrect');
    }
}
