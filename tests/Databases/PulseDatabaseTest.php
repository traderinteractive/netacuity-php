<?php

namespace TraderInteractive\NetAcuity\Databases\Tests;

use TraderInteractive\NetAcuity\Databases\PulseDatabase;
use TraderInteractive\NetAcuity\Tests\NetAcuityTestSuite;

/**
 * @coversDefaultClass \TraderInteractive\NetAcuity\Databases\PulseDatabase
 * @covers ::<private>
 */
class PulseDatabaseTest extends NetAcuityTestSuite
{
    /**
     * @test
     * @covers ::__construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $actual = new PulseDatabase($this->getMockGuzzleClient(), 'a token');
        $this->assertInstanceOf(PulseDatabase::class, $actual);
    }
}
