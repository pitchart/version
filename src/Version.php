<?php

namespace Pitchart\Version;

class Version
{
    private static $expression = '/^(?<major>(0|[1-9]\d*))\.(?<minor>(0|[1-9]\d*))\.(?<patch>(0|[1-9]\d*))(-(?<prerelease>[0-9a-zA-Z.]+))?(\+(?<build>[0-9a-zA-Z.]+))?$/';

    private $major;

    private $minor;

    private $patch;

    private $preRelease;

    private $buildMetadata;

    public function __construct($number) {
        if (!preg_match(self::$expression, $number, $matches)) {
            throw VersionException::invalidVersionNumber($number);
        }
        $this->major = $matches['major'];
        $this->minor = $matches['minor'];
        $this->patch = $matches['patch'];
        $this->preRelease = isset($matches['prerelease']) ? $matches['prerelease'] : '';
        $this->buildMetadata = isset($matches['build']) ? $matches['build'] : '';
    }

    public static function isValidVersionNumber($number) {
        return preg_match(self::$expression, $number) != false;
    }

    public function __toString() {
        return sprintf(
            '%d.%d.%d%s%s', 
            $this->major, $this->minor, $this->patch, 
            rtrim('-'.$this->preRelease, '-'),
            rtrim('+'.$this->buildMetadata, '+')
        );
    }

    public function incrementPatch() {
        return new static(sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch + 1));
    }

    public function incrementMinor() {
        return new static(sprintf('%d.%d.0', $this->major, $this->minor + 1));
    }

    public function incrementMajor() {
        return new static(sprintf('%d.0.0', $this->major + 1));
    }

    public function withPreRelease($preReleaseVersion) {
        $self = clone $this;
        $self->preRelease = $preReleaseVersion;
        return $self;
    }

    public function withBuildMetadata($buildMetadata) {
        $self = clone $this;
        $self->buildMetadata = $buildMetadata;
        return $self;
    }

}