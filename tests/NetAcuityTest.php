<?php

namespace DominionEnterprises\NetAcuity\Tests;
use DominionEnterprises\NetAcuity\NetAcuity;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\NetAcuity
 * @covers ::__construct
 */
final class NetAcuityTest extends NetAcuityTestSuite
{
    /**
     * @test
     * @covers ::getGeo
     *
     * @return void
     */
    public function getGeo()
    {
        $client = new NetAcuity(new TestDatabase());
        $actual = $client->getGeo('127.0.0.1');
        $expected = ['some' => 'data'];
        $this->assertSame($expected, $actual);
    }
}
