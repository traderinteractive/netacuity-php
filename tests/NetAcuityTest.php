<?php
namespace DominionEnterprises\NetAcuity;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \DominionEnterprises\NetAcuity\NetAcuity
 * @covers ::__construct
 * @covers ::<private>
 */
final class NetAcuityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::getGeo
     *
     * @return void
     */
    public function getGeoEdge()
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
        $client = new NetAcuity($mockClient, 'a token', NetAcuity::NETACUITY_EDGE_DB_ID);
        $actual = $client->getGeo('192.1681.1');

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
     * @covers ::getGeo
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
        $client = new NetAcuity($mockClient, 'a token', NetAcuity::NETACUITY_EDGE_DB_ID);

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
            $client->getGeo('1.2.3.4')
        );
    }

    /**
     * Verify that ip address must be a string.
     *
     * @test
     * @covers ::getGeo
     * @expectedException Exception
     * @expectedExceptionMessage NetAcuity API rejected the request, Reason: Invalid IP (1)
     * @expectedExceptionCode 400
     */
    public function getGeoNonStringIp()
    {
        $mockException = $this->getMockClientException(400, 'Invalid IP (1)');
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->will($this->throwException($mockException));
        $client = new NetAcuity($mockClient, 'a token', NetAcuity::NETACUITY_EDGE_DB_ID);
        $client->getGeo(1);
    }

    /**
     * @test
     *
     * @covers ::__construct
     *
     * @expectedException Exception
     * @expectedExceptionMessage NetAcuity DB Identifier: -1 not yet supported by this tool.
     */
    public function constructDbNotImplemented()
    {
        new NetAcuity($this->getMockGuzzleClient(), 'a token', -1);
    }

    /**
     * @test
     *
     * @covers ::getGeo
     *
     * @expectedException Exception
     * @expectedExceptionMessage NetAcuity API rejected the provided api user token.
     * @expectedExceptionCode 403
     */
    public function netAcuityUserTokenInvalid()
    {
        $mockException = $this->getMockClientException(403, 'Invalid IP (1)');
        $mockClient = $this->getMockGuzzleClient();
        $mockClient->method('send')->will($this->throwException($mockException));
        $client = new NetAcuity($mockClient, 'a token', NetAcuity::NETACUITY_EDGE_DB_ID);
        $client->getGeo('127.0.0.1');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Client
     */
    private function getMockGuzzleClient() : \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->setMethods(['send'])->getMock();
    }

    /**
     * @param int    $code         The desired error code.
     * @param string $errorMessage The desired embedded error message.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientException
     */
    private function getMockClientException(int $code, string $errorMessage) : \PHPUnit_Framework_MockObject_MockObject
    {
        $mockStream =$this->getMockBuilder('\GuzzleHttp\Psr7\Stream')->disableOriginalConstructor()->setMethods(['getContents'])->getMock();
        $mockStream->method('getContents')->willReturn(json_encode(['error' => ['message' => $errorMessage]]));

        $mockResponse = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')->disableOriginalConstructor()->setMethods(['getStatusCode', 'getBody'])->getMock();
        $mockResponse->method('getStatusCode')->willReturn($code);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockException = $this->getMockBuilder('\GuzzleHttp\Exception\ClientException')->disableOriginalConstructor()->setMethods(['getResponse'])->getMock();
        $mockException->method('getResponse')->willReturn($mockResponse);

        return $mockException;
    }

    /**
     * @param array $response The mocked response array.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Response
     */
    private function getMockResponse(array $response) : \PHPUnit_Framework_MockObject_MockObject
    {
        $mockStream = $this->getMockBuilder('\GuzzleHttp\Psr7\Stream')->disableOriginalConstructor()->setMethods(['getContents'])
            ->getMock();
        $mockStream->method('getContents')->willReturn(json_encode(['response' => $response], true));

        $mockResponse = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')->disableOriginalConstructor()->setMethods(['getBody'])
            ->getMock();
        $mockResponse->method('getBody')->willReturn($mockStream);

        return $mockResponse;
    }
}
