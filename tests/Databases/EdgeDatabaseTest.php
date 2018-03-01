<?php

namespace TraderInteractive\NetAcuity\Databases\Tests;

use TraderInteractive\NetAcuity\Databases\EdgeDatabase;
use TraderInteractive\NetAcuity\Tests\NetAcuityTestSuite;

/**
 * @coversDefaultClass \TraderInteractive\NetAcuity\Databases\EdgeDatabase
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
