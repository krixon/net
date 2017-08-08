<?php

namespace Krixon\Net;

class IPv4Address
{
    private $address;


    public function __construct(int $address)
    {
        if ($address > ip2long('255.255.255.255') || $address < 0) {
            throw new Exception\InvalidIPAddress($address);
        }

        $this->address = $this->pack($address);
    }


    public static function fromString(string $address) : self
    {
        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw new Exception\InvalidIPAddress($address);
        }

        return new static(ip2long($address));
    }


    public static function fromInteger(int $address) : self
    {
        return new static($address);
    }


    /**
     * Returns the IP address as a binary string.
     *
     * @return string
     */
    public function toBinaryString() : string
    {
        return $this->address;
    }


    /**
     * Returns the IP address as a dotted string (eg 10.0.0.1).
     *
     * @return string
     */
    public function toString() : string
    {
        return long2ip($this->toInteger());
    }


    /**
     * Returns the IP address as an integer.
     *
     * @return int
     */
    public function toInteger() : int
    {
        return $this->unpack($this->address);
    }


    /**
     * Returns an IPv6Address instance which corresponds to this IPv4 address.
     *
     * @return IPv6Address
     */
    public function toIpv6Address() : IPv6Address
    {
        $address = unpack('H*', $this->address)[1];
        $address = join(':', str_split($address, 4));
        $address = '::ffff:' . $address;

        return new IPv6Address($address);
    }


    private function pack(int $address) : string
    {
        return pack('N', $address);
    }


    private function unpack(string $address) : int
    {
        return unpack('N', $address)[1];
    }
}
