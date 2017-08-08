<?php

namespace Krixon\NetTests;

use Krixon\Net\IPv6Address;
use PHPUnit\Framework\TestCase;

class IPv6AddressTest extends TestCase
{
    /**
     * @dataProvider expandedToCompressedMapProvider
     *
     * @param string $expected
     * @param string $address
     */
    public function testCanExpandAddress(string $expected, string $address)
    {
        $this->assertSame($expected, IPv6Address::expand($address));
    }


    public function expandedToCompressedMapProvider()
    {
        return [
            ['2607:f0d0:1002:0051:0000:0000:0000:0004', '2607:f0d0:1002:51::4'],
        ];
    }


    /**
     * @dataProvider compressedToExpandedMapProvider
     *
     * @param string $expected
     * @param string $address
     */
    public function testCanCompressAddress(string $expected, string $address)
    {
        $this->assertSame($expected, IPv6Address::compress($address));
    }


    public function compressedToExpandedMapProvider()
    {
        return array_map(function($input) {
            return array_reverse($input);
        }, $this->expandedToCompressedMapProvider());
    }


    /**
     * @dataProvider validIpStringProvider
     *
     * @param string $expected
     * @param string $address
     */
    public function testCanConstructFromValidString(string $expected, string $address)
    {
        try {
            $ip = new IPv6Address($address);
        } catch (\InvalidArgumentException $e) {
            $this->fail("Could not construct IpV6Address instance from address '$address': " . $e->getMessage());
            return;
        }

        $this->assertSame($expected, $ip->toString(true));
    }


    public function validIpStringProvider()
    {
        return [
            ['2607:f0d0:1002:0051:0000:0000:0000:0004', '2607:f0d0:1002:51::4'],
            ['2607:f0d0:1002:0051:0000:0000:0000:0004', '2607:f0d0:1002:0051:0000:0000:0000:0004'],
            ['2001:0db8:85a3:0000:0000:8a2e:0370:7334', '2001:db8:85a3::8a2e:370:7334'],
        ];
    }
}
