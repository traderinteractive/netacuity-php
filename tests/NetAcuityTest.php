<?php
namespace DominionEnterprises\NetAcuity;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\NetAcuity
 * @covers ::__construct
 * @covers ::<private>
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
     * @covers ::getGeo
     */
    public function getGeo()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->setMethods(['write', 'read'])->getMock();
        $socket->expects($this->once())->method('write')->with("4;1;1.2.3.4\r\n")->will($this->returnValue(13));
        $socket->expects($this->once())->method('read')->with(1024)->will(
            $this->returnValue('xxxxUSA;something;reserved;broadband;2;123.456;789.101;12345;112;1314;1516;1;US;1;123;2;3;4;5;6;7;UTCxxx')
        );

        $client = new NetAcuity($socket, 1);

        $this->assertSame(
            [
                'country' => 'USA',
                'region' => 'something',
                'city' => 'reserved',
                'conn-speed' => 'broadband',
                'metro-code' => '2',
                'latitude' => '123.456',
                'longitude' => '789.101',
                'zip-code' => '12345',
                'country-code' => '112',
                'region-code' => '1314',
                'city-code' => '1516',
                'continent-code' => '1',
                'two-letter-country' => 'US',
                'internal-code' => '1',
                'area-code' => '123',
                'country-conf' => '2',
                'region-conf' => '3',
                'city-conf' => '4',
                'postal-conf' => '5',
                'gmt-offset' => '6',
                'in-dist' => '7',
                'timezone-name' => 'UTC',
            ],
            $client->getGeo('1.2.3.4')
        );
    }

    /**
     * Verify that ip address must be a string.
     *
     * @test
     * @covers ::getGeo
     * @expectedException InvalidArgumentException
     */
    public function getGeoNonStringIp()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->getMock();
        $client = new NetAcuity($socket, 1);
        $client->getGeo(1);
    }

    /**
     * @test
     * @covers ::getGeo
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Net acuity returned less than 22 fields
     */
    public function getGeoNotEnoughFields()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->setMethods(['write', 'read'])->getMock();
        $socket->expects($this->once())->method('write')->with("4;1;1.2.3.4\r\n")->will($this->returnValue(13));
        $socket->expects($this->once())->method('read')->with(1024)->will(
            $this->returnValue('xxxxUSA;something;reserved;broadband;2;123.456;789.101;12345;112;1314;1516;1;US;1;123;2;3;4;5;6;7')
        );

        $client = new NetAcuity($socket, 1);
        $client->getGeo('1.2.3.4');
    }

    /**
     *
     * @test
     * @covers ::getGeo
     */
    public function getGeoWithExtraField()
    {
        $socket = $this->getMockBuilder('\Socket\Raw\Socket')->disableOriginalConstructor()->setMethods(['write', 'read'])->getMock();
        $socket->expects($this->once())->method('write')->with("4;1;1.2.3.4\r\n")->will($this->returnValue(13));
        $socket->expects($this->once())->method('read')->with(1024)->will(
            $this->returnValue(
                'xxxxUSA;something;reserved;broadband;2;123.456;789.101;12345;112;1314;1516;1;US;1;123;2;3;4;5;6;7;UTC;extra dataxxx'
            )
        );

        $client = new NetAcuity($socket, 1);

        $this->assertSame(
            [
                'country' => 'USA',
                'region' => 'something',
                'city' => 'reserved',
                'conn-speed' => 'broadband',
                'metro-code' => '2',
                'latitude' => '123.456',
                'longitude' => '789.101',
                'zip-code' => '12345',
                'country-code' => '112',
                'region-code' => '1314',
                'city-code' => '1516',
                'continent-code' => '1',
                'two-letter-country' => 'US',
                'internal-code' => '1',
                'area-code' => '123',
                'country-conf' => '2',
                'region-conf' => '3',
                'city-conf' => '4',
                'postal-conf' => '5',
                'gmt-offset' => '6',
                'in-dist' => '7',
                'timezone-name' => 'UTC',
            ],
            $client->getGeo('1.2.3.4')
        );
    }
}
