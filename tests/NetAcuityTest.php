<?php
namespace DominionEnterprises\NetAcuity;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\NetAcuity
 */
final class NetAcuityTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that api id must be an integer.
     *
     * @test
     * @covers ::__construct
     * @expectedException InvalidArgumentException
     */
    public function createNonIntApiId()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->getMock();
        $client = new NetAcuity($socket, 'bar');
    }

    /**
     * Verify that geo lookup works.
     *
     * @test
     * @covers ::__construct
     * @covers ::getGeo
     * @covers ::_buildQuery
     * @covers ::_query
     * @covers ::_parseResponse
     */
    public function getGeo()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->setMethods(array('write', 'read'))->getMock();
        $socket->expects($this->once())->method('write')->with("3;1;1.2.3.4\r\n")->will($this->returnValue(13));
        $socket->expects($this->once())->method('read')->with(1024)->will(
            $this->returnValue('xxxxUSA;something;reserved;broadband;5;4;3;2;123.456;789.101;112;1314;1516;1;USxxx')
        );

        $client = new NetAcuity($socket, 1);

        $this->assertSame(
            array(
                'country' => 'USA',
                'region' => 'something',
                'city' => 'reserved',
                'conn-speed' => 'broadband',
                'country-conf' => '5',
                'region-conf' => '4',
                'city-conf' => '3',
                'metro-code' => '2',
                'latitude' => '123.456',
                'longitude' => '789.101',
                'country-code' => '112',
                'region-code' => '1314',
                'city-code' => '1516',
                'continent-code' => '1',
                'two-letter-country' => 'US',
            ),
            $client->getGeo('1.2.3.4')
        );
    }

    /**
     * Verify that ip address must be a string.
     *
     * @test
     * @covers ::__construct
     * @covers ::getGeo
     * @expectedException InvalidArgumentException
     */
    public function getGeoNonStringIp()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->getMock();
        $client = new NetAcuity($socket, 1);
        $client->getGeo(1);
    }
}
