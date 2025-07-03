<?php

namespace TraderInteractive\NetAcuity\Databases\Tests;

use TraderInteractive\NetAcuity\Databases\EdgeDatabase;
use TraderInteractive\NetAcuity\Exceptions\NetacuityException;
use TraderInteractive\NetAcuity\Tests\NetAcuityTestSuite;
use Exception;

/**
 * @coversDefaultClass \TraderInteractive\NetAcuity\Databases\AbstractNetAcuityDatabase
 * @covers ::__construct
 * @covers ::<protected>
 * @covers ::<private>
 */
final class AbstractNetAcuityDatabaseTest extends NetAcuityTestSuite
{
    /**
     * @test
     * @covers ::fetch
     *
     * @return void
     */
    public function fetchUsingEdge()
    {
        $mockResponse = $this->getMockResponse(
            [
                'edge-country' => 'america/new_york',
                'edge-region' => 'usa',
                'edge-city' => 'radford',
                'edge-conn-speed' => 'broadband',
                'edge-metro-code' => '2',
                'edge-latitude' => '123.456',
                'edge-longitude' => '789.101',
                'edge-postal-code' => '24141',
                'edge-country-code' => '112',
                'edge-region-code' => '1314',
                'edge-city-code' => '1516',
                'edge-continent-code' => '1',
                'edge-two-letter-country' => 'US',
                'edge-area-codes' => '540',
                'edge-country-conf' => '30039',
                'edge-region-conf' => '33.9056',
                'edge-city-conf' => '99',
                'edge-postal-conf' => '99',
                'edge-gmt-offset' => '-500',
                'edge-in-dst' => 'n',
                'edge-timezone-name' => 't3',
            ]
        );
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->willReturn($mockResponse);

        $database = new EdgeDatabase($mockClient, 'a token');
        $actual = $database->fetch('192.168.1.1');

        $expected = [
            'area-code' => '540',
            'city' => 'radford',
            'city-code' => '1516',
            'city-conf' => '99',
            'conn-speed' => 'broadband',
            'continent-code' => '1',
            'country' => 'america/new_york',
            'country-code' => '112',
            'country-conf' => '30039',
            'gmt-offset' => '-500',
            'in-dist' => 'n',
            'latitude' => '123.456',
            'longitude' => '789.101',
            'metro-code' => '2',
            'postal-conf' => '99',
            'region' => 'usa',
            'region-code' => '1314',
            'region-conf' => '33.9056',
            'timezone-name' => 't3',
            'two-letter-country' => 'US',
            'zip-code' => '24141',
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     *
     * @test
     * @covers ::fetch
     */
    public function getGeoWithExtraFieldEdge()
    {
        $mockResponse = $this->getMockResponse(
            [
                'edge-country' => 'USA',
                'edge-region' => 'something',
                'edge-city' => 'reserved',
                'edge-conn-speed' => 'broadband',
                'edge-metro-code' => '2',
                'edge-latitude' => '123.456',
                'edge-longitude' => '789.101',
                'edge-postal-code' => '12345',
                'edge-country-code' => '112',
                'edge-region-code' => '1314',
                'edge-city-code' => '1516',
                'edge-continent-code' => '1',
                'edge-two-letter-country' => 'US',
                'edge-area-codes' => '123',
                'edge-country-conf' => '2',
                'edge-region-conf' => '3',
                'edge-city-conf' => '4',
                'edge-postal-conf' => '5',
                'edge-gmt-offset' => '6',
                'edge-in-dst' => '7',
                'edge-timezone-name' => 'UTC',
                'edge-extra' => 'erroneous',
            ]
        );
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->willReturn($mockResponse);

        $database = new EdgeDatabase($mockClient, 'a token');

        $this->assertSame(
            [
                'area-code' => '123',
                'city' => 'reserved',
                'city-code' => '1516',
                'city-conf' => '4',
                'conn-speed' => 'broadband',
                'continent-code' => '1',
                'country' => 'USA',
                'country-code' => '112',
                'country-conf' => '2',
                'gmt-offset' => '6',
                'in-dist' => '7',
                'latitude' => '123.456',
                'longitude' => '789.101',
                'metro-code' => '2',
                'postal-conf' => '5',
                'region' => 'something',
                'region-code' => '1314',
                'region-conf' => '3',
                'timezone-name' => 'UTC',
                'two-letter-country' => 'US',
                'zip-code' => '12345',
            ],
            $database->fetch('1.2.3.4')
        );
    }

    /**
     * Verify that ip address must be a string.
     *
     * @test
     * @covers ::fetch
     */
    public function getGeoNonStringIp()
    {
        $this->expectException(NetacuityException::class);
        $this->expectExceptionMessage('NetAcuity API rejected the request, Reason: Invalid IP (1)');
        $this->expectExceptionCode(400);

        $mockException = $this->getMockClientException(400, 'Invalid IP (1)');
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->will($this->throwException($mockException));

        $database = new EdgeDatabase($mockClient, 'a token');
        $database->fetch(1);
    }

    /**
     * @test
     * @covers ::fetch
     */
    public function netAcuityUserTokenInvalid()
    {
        $this->expectException(NetacuityException::class);
        $this->expectExceptionMessage('NetAcuity API rejected the provided api user token.');
        $this->expectExceptionCode(403);

        $mockException = $this->getMockClientException(403, 'Invalid IP (1)');
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->will($this->throwException($mockException));

        $database = new EdgeDatabase($mockClient, 'a token');
        $database->fetch('127.0.0.1');
    }
}
