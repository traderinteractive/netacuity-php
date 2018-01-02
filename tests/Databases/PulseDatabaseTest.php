<?php

namespace DominionEnterprises\NetAcuity\Databases\Tests;

use DominionEnterprises\NetAcuity\Databases\PulseDatabase;
use DominionEnterprises\NetAcuity\Tests\NetAcuityTestSuite;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\Databases\PulseDatabase
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
