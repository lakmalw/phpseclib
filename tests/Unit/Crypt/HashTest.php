<?php
/**
 * @author    Andreas Fischer <bantu@phpbb.com>
 * @copyright 2012 Andreas Fischer
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use phpseclib\Crypt\Hash;

class Unit_Crypt_HashTest extends PhpseclibTestCase
{
    protected function assertHashesTo($hash, $message, $expected)
    {
        $hash = new Hash($hash);

        $this->assertSame(
            strtolower($expected),
            bin2hex($hash->hash($message)),
            sprintf("Failed asserting that '%s' hashes to '%s'.", $message, $expected)
        );
    }

    protected function assertHMACsTo($hash, $key, $message, $expected)
    {
        $hash = new Hash($hash);
        $hash->setKey($key);

        $this->assertSame(
            strtolower($expected),
            bin2hex($hash->hash($message)),
            sprintf(
                "Failed asserting that '%s' HMACs to '%s' with key '%s'.",
                $message,
                $expected,
                $key
            )
        );
    }

    public static function hashData()
    {
        return array(
            array('md5', '', 'd41d8cd98f00b204e9800998ecf8427e'),
            array('md5', 'The quick brown fox jumps over the lazy dog', '9e107d9d372bb6826bd81d3542a419d6'),
            array('md5', 'The quick brown fox jumps over the lazy dog.', 'e4d909c290d0fb1ca068ffaddf22cbd0'),
            array('sha1', 'The quick brown fox jumps over the lazy dog', '2fd4e1c67a2d28fced849ee1bb76e7391b93eb12'),
            array('sha1', 'The quick brown fox jumps over the lazy dog.', '408d94384216f890ff7a0c3528e8bed1e0b01621'),
            array(
                'sha256',
                '',
                'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'
            ),
            array(
                'sha256',
                'The quick brown fox jumps over the lazy dog',
                'd7a8fbb307d7809469ca9abcb0082e4f8d5651e46d3cdb762d02d0bf37c9e592',
            ),
            array(
                'sha256',
                'The quick brown fox jumps over the lazy dog.',
                'ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c',
            ),
            array(
                'sha384',
                '',
                '38b060a751ac96384cd9327eb1b1e36a21fdb71114be07434c0cc7bf63f6e1da274edebfe76f65fbd51ad2f14898b95b'
            ),
            array(
                'sha384',
                'The quick brown fox jumps over the lazy dog',
                'ca737f1014a48f4c0b6dd43cb177b0afd9e5169367544c494011e3317dbf9a509cb1e5dc1e85a941bbee3d7f2afbc9b1',
            ),
            array(
                'sha512',
                '',
                'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e'
            ),
            array(
                'sha512',
                'The quick brown fox jumps over the lazy dog',
                '07e547d9586f6a73f73fbac0435ed76951218fb7d0c8d788a309d785436bbb642e93a252a954f23912547d1e8a3b5ed6e1bfd7097821233fa0538f3db854fee6',
            ),
            array(
                'sha512',
                'The quick brown fox jumps over the lazy dog.',
                '91ea1245f20d46ae9a037a989f54f1f790f0a47607eeb8a14d12890cea77a1bbc6c7ed9cf205e67b7f2b8fd4c7dfd3a7a8617e45f3c463d481c7e586c39ac1ed',
            ),
            array(
                'whirlpool',
                'The quick brown fox jumps over the lazy dog.',
                '87a7ff096082e3ffeb86db10feb91c5af36c2c71bc426fe310ce662e0338223e217def0eab0b02b80eecf875657802bc5965e48f5c0a05467756f0d3f396faba'
            ),
            array(
                'whirlpool',
                'The quick brown fox jumps over the lazy dog.',
                '87a7ff096082e3ffeb86db10feb91c5af36c2c71bc426fe310ce662e0338223e217def0eab0b02b80eecf875657802bc5965e48f5c0a05467756f0d3f396faba'
            ),
            array(
                'whirlpool',
                'The quick brown fox jumps over the lazy dog.',
                '87a7ff096082e3ffeb86db10feb91c5af36c2c71bc426fe310ce662e0338223e217def0eab0b02b80eecf875657802bc5965e48f5c0a05467756f0d3f396faba'
            ),
            // from http://csrc.nist.gov/groups/ST/toolkit/documents/Examples/SHA512_224.pdf
            array(
                'sha512/224',
                'abc',
                '4634270f707b6a54daae7530460842e20e37ed265ceee9a43e8924aa'
            ),
            array(
                'sha512/224',
                'abcdefghbcdefghicdefghijdefghijkefghijklfghijklmghijklmnhijklmnoijklmnopjklmnopqklmnopqrlmnopqrsmnopqrstnopqrstu',
                '23fec5bb94d60b23308192640b0c453335d664734fe40e7268674af9'
            ),
            // from http://csrc.nist.gov/groups/ST/toolkit/documents/Examples/SHA512_256.pdf
            array(
                'sha512/256',
                'abc',
                '53048e2681941ef99b2e29b76b4c7dabe4c2d0c634fc6d46e0e2f13107e7af23'
            ),
            array(
                'sha512/256',
                'abcdefghbcdefghicdefghijdefghijkefghijklfghijklmghijklmnhijklmnoijklmnopjklmnopqklmnopqrlmnopqrsmnopqrstnopqrstu',
                '3928e184fb8690f840da3988121d31be65cb9d3ef83ee6146feac861e19b563a'
            ),
            // from http://csrc.nist.gov/groups/ST/toolkit/documents/Examples/SHA224.pdf
            array(
                'sha224',
                'abc',
                '23097D223405D8228642A477BDA255B32AADBCE4BDA0B3F7E36C9DA7'
            ),
            array(
                'sha224',
                'abcdbcdecdefdefgefghfghighijhijkijkljklmklmnlmnomnopnopq',
                '75388B16512776CC5DBA5DA1FD890150B0C6455CB4F58B1952522525'
            ),
        );
    }

    /**
     * @dataProvider hmacData()
     */
    public function testHMAC($hash, $key, $message, $result)
    {
        $this->assertHMACsTo($hash, $key, $message, $result);
    }

    /**
     * @dataProvider hmacData()
     */
    public function testHMAC96($hash, $key, $message, $result)
    {
        $this->assertHMACsTo($hash . '-96', $key, $message, substr($result, 0, 24));
    }

    public static function hmacData()
    {
        return array(
            array('md5', '', '', '74e6f7298a9c2d168935f58c001bad88'),
            array('md5', 'key', 'The quick brown fox jumps over the lazy dog', '80070713463e7749b90c2dc24911e275'),

            array(
                'whirlpool',
                'abcd',
                'The quick brown fox jumps over the lazy dog',
                'e71aabb2588d789292fa6fef00b35cc269ec3ea912b1c1cd7127daf95f004a5df5392ee563d322bac7e19d9eab161932fe9c257d63e0d09eca0d91ab4010125e',
            ),

            // from https://tools.ietf.org/rfc/rfc4231.txt
            // test case 1
            array(
                'sha224',
                str_repeat("\x0b", 20),
                'Hi There',
                '896fb1128abbdf196832107cd49df33f47b4b1169912ba4f53684b22',
            ),
            // test case 2
            array(
                'sha224',
                'Jefe',
                'what do ya want for nothing?',
                'a30e01098bc6dbbf45690f3a7e9e6d0f8bbea2a39e6148008fd05e44',
            ),
            // test case 3
            array(
                'sha224',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                pack('H*', 'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'),
                '7fb3cb3588c6c1f6ffa9694d7d6ad2649365b0c1f65d69d1ec8333ea',
            ),
            // test case 4
            array(
                'sha224',
                pack('H*', '0102030405060708090a0b0c0d0e0f10111213141516171819'),
                pack('H*', 'cdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcd'),
                '6c11506874013cac6a2abc1bb382627cec6a90d86efc012de7afec5a',
            ),
            // skip test case 5; truncation is only supported to 96 bits (eg. sha1-96) and that's already unit tested
            // test case 6
            array(
                'sha224',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'Test Using Larger Than Block-Size Key - Hash Key First',
                '95e9a0db962095adaebe9b2d6f0dbce2d499f112f2d2b7273fa6870e',
            ),
            // test case 7
            array(
                'sha224',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'This is a test using a larger than block-size key and a larger than block-size data. The key needs to be hashed before being used by the HMAC algorithm.',
                '3a854166ac5d9f023f54d517d0b39dbd946770db9c2b95c9f6f565d1'
            ),

            // test case 1
            array(
                'sha256',
                str_repeat("\x0b", 20),
                'Hi There',
                'b0344c61d8db38535ca8afceaf0bf12b881dc200c9833da726e9376c2e32cff7',
            ),
            // test case 2
            array(
                'sha256',
                'Jefe',
                'what do ya want for nothing?',
                '5bdcc146bf60754e6a042426089575c75a003f089d2739839dec58b964ec3843',
            ),
            // test case 3
            array(
                'sha256',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                pack('H*', 'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'),
                '773ea91e36800e46854db8ebd09181a72959098b3ef8c122d9635514ced565fe',
            ),
            // test case 4
            array(
                'sha256',
                pack('H*', '0102030405060708090a0b0c0d0e0f10111213141516171819'),
                pack('H*', 'cdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcd'),
                '82558a389a443c0ea4cc819899f2083a85f0faa3e578f8077a2e3ff46729665b',
            ),
            // skip test case 5; truncation is only supported to 96 bits (eg. sha1-96) and that's already unit tested
            // test case 6
            array(
                'sha256',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'Test Using Larger Than Block-Size Key - Hash Key First',
                '60e431591ee0b67f0d8a26aacbf5b77f8e0bc6213728c5140546040f0ee37f54',
            ),
            // test case 7
            array(
                'sha256',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'This is a test using a larger than block-size key and a larger than block-size data. The key needs to be hashed before being used by the HMAC algorithm.',
                '9b09ffa71b942fcb27635fbcd5b0e944bfdc63644f0713938a7f51535c3a35e2'
            ),

            // test case 1
            array(
                'sha384',
                str_repeat("\x0b", 20),
                'Hi There',
                'afd03944d84895626b0825f4ab46907f15f9dadbe4101ec682aa034c7cebc59cfaea9ea9076ede7f4af152e8b2fa9cb6',
            ),
            // test case 2
            array(
                'sha384',
                'Jefe',
                'what do ya want for nothing?',
                'af45d2e376484031617f78d2b58a6b1b9c7ef464f5a01b47e42ec3736322445e8e2240ca5e69e2c78b3239ecfab21649',
            ),
            // test case 3
            array(
                'sha384',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                pack('H*', 'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'),
                '88062608d3e6ad8a0aa2ace014c8a86f0aa635d947ac9febe83ef4e55966144b2a5ab39dc13814b94e3ab6e101a34f27',
            ),
            // test case 4
            array(
                'sha384',
                pack('H*', '0102030405060708090a0b0c0d0e0f10111213141516171819'),
                pack('H*', 'cdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcd'),
                '3e8a69b7783c25851933ab6290af6ca77a9981480850009cc5577c6e1f573b4e6801dd23c4a7d679ccf8a386c674cffb',
            ),
            // skip test case 5; truncation is only supported to 96 bits (eg. sha1-96) and that's already unit tested
            // test case 6
            array(
                'sha384',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'Test Using Larger Than Block-Size Key - Hash Key First',
                '4ece084485813e9088d2c63a041bc5b44f9ef1012a2b588f3cd11f05033ac4c60c2ef6ab4030fe8296248df163f44952',
            ),
            // test case 7
            array(
                'sha384',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'This is a test using a larger than block-size key and a larger than block-size data. The key needs to be hashed before being used by the HMAC algorithm.',
                '6617178e941f020d351e2f254e8fd32c602420feb0b8fb9adccebb82461e99c5a678cc31e799176d3860e6110c46523e'
            ),

            // test case 1
            array(
                'sha512',
                str_repeat("\x0b", 20),
                'Hi There',
                '87aa7cdea5ef619d4ff0b4241a1d6cb02379f4e2ce4ec2787ad0b30545e17cdedaa833b7d6b8a702038b274eaea3f4e4be9d914eeb61f1702e696c203a126854',
            ),
            // test case 2
            array(
                'sha512',
                'Jefe',
                'what do ya want for nothing?',
                '164b7a7bfcf819e2e395fbe73b56e0a387bd64222e831fd610270cd7ea2505549758bf75c05a994a6d034f65f8f0e6fdcaeab1a34d4a6b4b636e070a38bce737',
            ),
            // test case 3
            array(
                'sha512',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                pack('H*', 'dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'),
                'fa73b0089d56a284efb0f0756c890be9b1b5dbdd8ee81a3655f83e33b2279d39bf3e848279a722c806b485a47e67c807b946a337bee8942674278859e13292fb',
            ),
            // test case 4
            array(
                'sha512',
                pack('H*', '0102030405060708090a0b0c0d0e0f10111213141516171819'),
                pack('H*', 'cdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcdcd'),
                'b0ba465637458c6990e5a8c5f61d4af7e576d97ff94b872de76f8050361ee3dba91ca5c11aa25eb4d679275cc5788063a5f19741120c4f2de2adebeb10a298dd',
            ),
            // skip test case 5; truncation is only supported to 96 bits (eg. sha1-96) and that's already unit tested
            // test case 6
            array(
                'sha512',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'Test Using Larger Than Block-Size Key - Hash Key First',
                '80b24263c7c1a3ebb71493c1dd7be8b49b46d1f41b4aeec1121b013783f8f3526b56d037e05f2598bd0fd2215d6a1e5295e64f73f63f0aec8b915a985d786598',
            ),
            // test case 7
            array(
                'sha512',
                pack('H*', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
                'This is a test using a larger than block-size key and a larger than block-size data. The key needs to be hashed before being used by the HMAC algorithm.',
                'e37b6a775dc87dbaa4dfa9f96e5e3ffddebd71f8867289865df5a32d20cdc944b6022cac3c4982b10d5eeb55c3e4de15134676fb6de0446065c97440fa8c6a58'
            ),
        );
    }

    /**
     * @dataProvider hashData()
     */
    public function testHash($hash, $message, $result)
    {
        $this->assertHashesTo($hash, $message, $result);
    }

    /**
     * @dataProvider hashData()
     */
    public function testHash96($hash, $message, $result)
    {
        $this->assertHashesTo($hash . '-96', $message, substr($result, 0, 24));
    }

    public function testConstructorDefault()
    {
        $hash = new Hash();
        $this->assertSame($hash->getHash(), 'sha256');
    }

    /**
     * @expectedException \phpseclib\Exception\UnsupportedAlgorithmException
     */
    public function testConstructorArgumentInvalid()
    {
        new Hash('abcdefghijklmnopqrst');
    }

    public function testConstructorArgumentValid()
    {
        $hash = new Hash('whirlpool');
        $this->assertSame($hash->getHash(), 'whirlpool');
    }

    /**
     * @expectedException \phpseclib\Exception\UnsupportedAlgorithmException
     */
    public function testSetHashInvalid()
    {
        $hash = new Hash('md5');
        $hash->setHash('abcdefghijklmnopqrst-96');
    }

    public function testSetHashValid()
    {
        $hash = new Hash('md5');
        $this->assertSame($hash->getHash(), 'md5');
        $hash->setHash('sha1');
        $this->assertSame($hash->getHash(), 'sha1');
    }

    /**
     * @dataProvider lengths
     */
    public function testGetLengthKnown($algorithm, $length)
    {
        $hash = new Hash($algorithm);
        $this->assertSame($hash->getLength(), $length);
    }

    public function lengths()
    {
        return array(
            // known
            array('md5-96', 12),
            array('md5', 16),
            array('sha1', 20),
            array('sha256', 32),
            array('sha384', 48),
            array('sha512', 64),
            // unknown
            array('whirlpool', 64),
        );
    }
}
