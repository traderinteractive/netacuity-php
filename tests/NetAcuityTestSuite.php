<?php

namespace DominionEnterprises\NetAcuity\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

abstract class NetAcuityTestSuite extends PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Client
     */
    protected function getMockGuzzleClient() : \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->setMethods(['send'])->getMock();
    }

    /**
     * @param int    $code         The desired error code.
     * @param string $errorMessage The desired embedded error message.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientException
     */
    protected function getMockClientException(int $code, string $errorMessage) : \PHPUnit_Framework_MockObject_MockObject
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
    protected function getMockResponse(array $response) : \PHPUnit_Framework_MockObject_MockObject
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
