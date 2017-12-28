<?php

namespace DominionEnterprises\NetAcuity\Databases\Tests;

use DominionEnterprises\NetAcuity\Databases\EdgeDatabase;
use DominionEnterprises\NetAcuity\Tests\NetAcuityTestSuite;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\Databases\EdgeDatabase
 * @covers ::<private>
 */
class EdgeDatabaseTest extends NetAcuityTestSuite
{
    /**
     * @test
     * @covers ::__construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $actual = new EdgeDatabase($this->getMockGuzzleClient(), 'a token');
        $this->assertInstanceOf(EdgeDatabase::class, $actual);
    }
}
