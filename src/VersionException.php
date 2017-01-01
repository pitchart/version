<?php

namespace Pitchart\Version;

class VersionException extends \InvalidArgumentException
{

    public static function invalidVersionNumber($number) {
        return new self($number.' is not a valid semantic versioning number');
    }
}