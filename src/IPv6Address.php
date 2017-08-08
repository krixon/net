<?php

namespace Krixon\Net;

class IPv6Address
{
    private $address;


    public function __construct(string $address)
    {
        if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw new Exception\InvalidIPAddress($address);
        }

        $this->address = $this->pack($this->expand($address));
    }


    public static function fromString(string $address) : self
    {
        return new static($address);
    }


    /**
     * Returns an array of 8 x 16-bit unsigned integers representing the address.
     *
     * The array is numerically indexed from 0 to 7. The most-significant 16bits are at position 0, and the
     * least-significant at position 7.
     *
     * @return int[]
     */
    public function toIntegerArray() : array
    {
        return $this->unpack($this->address);
    }


    /**
     * Returns the IPv6 address as a string.
     *
     * @param bool $expanded True to return the expanded form of the address, false to return the compressed form.
     *
     * @return string
     */
    public function toString($expanded = false) : string
    {
        $result = inet_ntop($this->address);

        return $expanded ? static::expand($result) : $result;
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
     * Expands an IPv6 address.
     *
     * For example, 2001:630:d0:: -> 2001:0630:00d0:0000:0000:0000:0000:0000
     *
     * @param string $address
     *
     * @return string
     */
    static public function expand(string $address)
    {
        $hex = unpack('H*hex', inet_pton($address));

        return substr(preg_replace('/([A-f0-9]{4})/', "$1:", $hex['hex']), 0, -1);
    }


    /**
     * Compresses an IPv6 address.
     *
     * For example, 2001:0630:00d0:0000:0000:0000:0000:0000 -> 2001:630:d0::
     *
     * @param string $address
     *
     * @return string
     */
    static public function compress($address)
    {
        return inet_ntop(inet_pton($address));
    }


    /**
     * Packs the string representation of the address into a binary string.
     *
     * @param string $address
     *
     * @return string
     */
    private function pack(string $address) : string
    {
        return inet_pton($address);
    }


    /**
     * Unpacks the address into an array of 8 x 16bit unsigned integers.
     *
     * @param string $address
     *
     * @return int[]
     */
    private function unpack($address) : array
    {
        return unpack('n*', $address);
    }
}
