<?php

namespace Pitchart\Version\Test\Unit;

use Pitchart\Version\Version;
use Composer\Semver\Comparator;
use Composer\Semver\Semver;

class ComposerSemverUsageTest extends \PHPUnit_Framework_TestCase
{

    public function testCanBeComparedWithSemverComparator() {
        $version = new Version('1.0.0');
        $this->assertTrue(Comparator::greaterThan($version->incrementPatch(), $version));
        $this->assertTrue(Comparator::greaterThanOrEqualTo($version->incrementPatch(), $version));
        $this->assertTrue(Comparator::lessThan($version, $version->incrementPatch()));
        $this->assertTrue(Comparator::lessThanOrEqualTo($version, $version->incrementPatch()));
        $this->assertTrue(Comparator::notEqualTo($version, $version->incrementPatch()));
        $this->assertTrue(Comparator::equalTo($version, '1.0.0'));
    }

    /**
     * @dataProvider sortProvider
     *
     * @param array $versions
     * @param array $sorted
     * @param array $rsorted
     */
    public function testCanBeSortedWithSemver(array $versions, array $sorted, array $rsorted)
    {
        $this->assertEquals($sorted, Semver::sort($versions));
        $this->assertEquals($rsorted, Semver::rsort($versions));
    }

    /**
     * @dataProvider satisfiesProvider
     */
    public function testCanSatisfyConstraints($number, $constraint) {
        $this->assertTrue(Semver::satisfies(new Version($number), $constraint));
    }

    /**
     * @return array()
     */
    public function satisfiesProvider() {
        return array(
            array('1.2.3', '1.0.0 - 2.0.0'),
            array('1.2.3', '^1.2.3+build'),
            array('1.0.0', '>= 1.0.0'),
            array('1.0.1', '>=  1.0.0'),
            array('1.1.0', '>=   1.0.0'),
            array('1.0.1', '> 1.0.0'),
            array('1.1.0', '>  1.0.0'),
            array('2.0.0', '<=   2.0.0'),
        );
    }

    /**
     * @return array
     */
    public function sortProvider()
    {
        return array(
            array(
                array(new Version('1.0.0'), new Version('0.1.0'), new Version('0.1.0'), new Version('3.2.1'), new Version('2.4.0-alpha'), new Version('2.4.0')),
                array(new Version('0.1.0'), new Version('0.1.0'), new Version('1.0.0'), new Version('2.4.0-alpha'), new Version('2.4.0'), new Version('3.2.1')),
                array(new Version('3.2.1'), new Version('2.4.0'), new Version('2.4.0-alpha'), new Version('1.0.0'), new Version('0.1.0'), new Version('0.1.0')),
            ),
        );
    }

}