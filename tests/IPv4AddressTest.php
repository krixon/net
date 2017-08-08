<?php

namespace Krixon\NetTests;

use Krixon\Net\Exception\InvalidIPAddress;
use Krixon\Net\IPv4Address;
use PHPUnit\Framework\TestCase;

class IPv4AddressTest extends TestCase
{
    /**
     * @dataProvider validIpStringProvider
     *
     * @param string $address
     */
    public function testCanConstructFromValidString($address)
    {
        try {
            $ip = IPv4Address::fromString($address);
        } catch (\InvalidArgumentException $e) {
            $this->fail("Could not construct IpAddress instance from address '$address': " . $e->getMessage());
            return;
        }

        $this->assertSame(ip2long($address), $ip->toInteger());
    }


    public function validIpStringProvider()
    {
        return [
            ['127.0.0.1'],
            ['192.168.0.100'],
            ['10.0.0.1'],
            ['255.255.255.255'],
            ['8.8.8.8'],
            ['8.8.4.4'],
        ];
    }


    /**
     * @dataProvider validIpIntegerProvider
     *
     * @param int $address
     */
    public function testCanConstructFromValidInteger(int $address)
    {
        try {
            $ip = IPv4Address::fromInteger($address);
        } catch (\InvalidArgumentException $e) {
            $this->fail("Could not construct IpAddress instance from address '$address': " . $e->getMessage());
            return;
        }

        $this->assertSame($address, $ip->toInteger());
    }


    /**
     * @dataProvider invalidIpStringProvider
     *
     * @param string $address
     */
    public function testCannotConstructFromInvalidString(string $address)
    {
        $this->expectException(InvalidIPAddress::class);

        IPv4Address::fromString($address);
    }


    public function invalidIpStringProvider()
    {
        return [
            ['255.255.255.256'],
            ['10.0.-1.0'],
            ['not an IP address at all'],
        ];
    }


    /**
     * @dataProvider invalidIpIntegerProvider
     *
     * @param int $address
     */
    public function testCannotConstructFromInvalidInteger(int $address)
    {
        $this->expectException(InvalidIPAddress::class);

        new IPv4Address($address);
    }


    public function invalidIpIntegerProvider()
    {
        return [
            [PHP_INT_MAX],
            [-1],
        ];
    }


    public function validIpIntegerProvider()
    {
        return array_map(function($item) {
            return [ip2long($item[0])];
        }, $this->validIpStringProvider());
    }


    public function testCanRetrieveAsBinaryString()
    {
        $address = '8.8.8.8';
        $ip      = IPv4Address::fromString($address);

        $this->assertSame(ip2long($address), unpack('N', $ip->toBinaryString())[1]);
    }


    public function testCanRetrieveAsString()
    {
        $address = '8.8.8.8';
        $ip      = IPv4Address::fromString($address);

        $this->assertSame($address, $ip->toString());
    }


    public function testCanRetrieveAsInteger()
    {
        $address = '8.8.8.8';
        $ip      = IPv4Address::fromString($address);

        $this->assertSame(ip2long($address), $ip->toInteger());
    }
}
