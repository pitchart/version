<?php

namespace Pitchart\Version\Test\Unit;

use Pitchart\Version\Version;
use Pitchart\Version\VersionException;

class VersionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $number
     * @dataProvider validVersionsProvider
     */
    public function testValidatesVersionNumbers($number)
    {
        $this->assertTrue(Version::isValidVersionNumber($number));
    }

    /**
     * @param string $number
     * @dataProvider invalidVersionsProvider
     */
    public function testInvalidatesWrongVersionNumbers($number)
    {
        $this->assertFalse(Version::isValidVersionNumber($number));
    }

    /**
     * @param string $number
     * @dataProvider validVersionsProvider
     */
    public function testCastAsStringRetrieveOriginalNumber($number)
    {
        $version = new Version($number);
        $this->assertEquals($number, (string) $version);
    }

    public function testMethodsKeppImutability()
    {
        $version = new Version('1.2.3-pre.release+today');

        $modifiedVersion = $version->incrementMajor()
            ->incrementMinor()
            ->incrementPatch()
            ->withPreRelease('anotherprerelease')
            ->withBuildMetadata('anotherday')
        ;

        $this->assertEquals('1.2.3-pre.release+today', (string) $version);
        $this->assertEquals('2.1.1-anotherprerelease+anotherday', (string) $modifiedVersion);
    }

    /**
     * @param string $number
     * @dataProvider invalidVersionsProvider
     */
    public function testCreateVersionFromInvalidNumberThrowsException($number)
    {
        $this->expectException(VersionException::class);
        $version = new Version($number);
    }

    public function testIncrementMinorNumberResetsPatchNumber()
    {
        $version = new Version('1.1.1');
        $version = $version->incrementMinor();
        $this->assertEquals('1.2.0', (string) $version);
    }

    public function testIncrementMajorNumberResetsPatchAndMinorNumbers()
    {
        $version = new Version('1.1.1');
        $version = $version->incrementMajor();
        $this->assertEquals('2.0.0', (string) $version);
    }

    public function validVersionsProvider()
    {
        return [
            ['0.9.14'],
            ['1.5.6'],
            ['1.6.3-dev'],
            ['2.4.6-stable'],
            ['1.0.0-alpha.1'],
            ['1.0.0-0.3.7'],
            ['1.0.0-x.7.z.92'],
            ['1.0.0-x.7-rc.z.92'],
            ['1.0.0-alpha+001'],
            ['1.0.0+20130313144700'],
            ['1.0.0-beta+exp.sha.5114f85'],
            ['1.0.0-beta.1+exp.sha.5114f85'],
        ];
    }

    public function invalidVersionsProvider()
    {
        return [
            ['1.0'],
            ['01.0.0'],
            ['1.01.0'],
            ['1.1.01'],
            ['1.1.0-.stable'],
            ['1.1.0-01'],
            ['1.1.0-stable.01'],
            ['1.1.0-+'],
            ['1.1.0-+test'],
            ['1.1.0-test+'],
            ['1.1.0-é+test'],
            ['1.1.0-test+é'],
        ];
    }
}
