<?php

namespace TraderInteractive\NetAcuity\Tests;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class NetAcuityTestSuite extends TestCase
{
    protected function getMockGuzzleClient() : ClientInterface
    {
        return $this->getMockBuilder(
            '\GuzzleHttp\Client'
        )->disableOriginalConstructor()->setMethods(['send'])->getMock();
    }

    protected function getMockClientException(int $code, string $errorMessage) : ClientException
    {
        $mockStream =$this->getMockBuilder(
            '\GuzzleHttp\Psr7\Stream'
        )->disableOriginalConstructor()->setMethods(['getContents'])->getMock();
        $mockStream->method(
            'getContents'
        )->willReturn(json_encode(['error' => ['message' => $errorMessage]]));

        $mockResponse = $this->getMockBuilder(
            '\GuzzleHttp\Psr7\Response'
        )->disableOriginalConstructor()->setMethods(['getStatusCode', 'getBody'])->getMock();
        $mockResponse->method('getStatusCode')->willReturn($code);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockException = $this->getMockBuilder(
            '\GuzzleHttp\Exception\ClientException'
        )->disableOriginalConstructor()->setMethods(['getResponse'])->getMock();
        $mockException->method('getResponse')->willReturn($mockResponse);

        return $mockException;
    }

    protected function getMockResponse(array $response) : ResponseInterface
    {
        $mockStream = $this->getMockBuilder(
            '\GuzzleHttp\Psr7\Stream'
        )->disableOriginalConstructor()->setMethods(['getContents'])->getMock();
        $mockStream->method('getContents')->willReturn(json_encode(['response' => $response], true));

        $mockResponse = $this->getMockBuilder(
            '\GuzzleHttp\Psr7\Response'
        )->disableOriginalConstructor()->setMethods(['getBody'])->getMock();
        $mockResponse->method('getBody')->willReturn($mockStream);

        return $mockResponse;
    }
}
