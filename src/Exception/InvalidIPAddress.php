<?php

namespace Krixon\Net\Exception;

class InvalidIPAddress extends \DomainException implements NetException
{
    public function __construct($address)
    {
        parent::__construct("Invalid IP address: $address.");
    }
}
