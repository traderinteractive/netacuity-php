<?php

namespace TraderInteractive\NetAcuity\Databases;

use TraderInteractive\NetAcuity\Exceptions\NetacuityException;
use TraderInteractive\Util\Arrays;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * The Abstract NetAcuityDatabase used as a base for each database model.
 */
abstract class AbstractNetAcuityDatabase implements NetAcuityDatabaseInterface
{
    /**
     * @var string
     * @internal
     */
    const DEFAULT_NETACUITY_BASE_URL = 'https://usa.cloud.netacuity.com/webservice/query';

    /**
     * @var ClientInterface The GuzzleHttp Client.
     */
    protected $client;

    /**
     * @var array The translations array for the data set.
     */
    protected $translations;

    /**
     * @var string
     */
    protected $netacuityBaseUrl;

    /**
     * @var string The API User Token.
     */
    protected $apiUserToken;

    /**
     * @var int The Net Acuity Database ID.
     */
    protected $databaseIdentifier;

    /**
     * AbstractNetAcuityDatabase constructor.
     *
     * @param ClientInterface $client           The injected GuzzleHttp Client.
     * @param string          $apiUserToken     The Net Acuity API User Token.
     * @param string          $netacuityBaseUrl The base url for the netacuity webservice.
     */
    public function __construct(
        ClientInterface $client,
        string $apiUserToken,
        string $netacuityBaseUrl = self::DEFAULT_NETACUITY_BASE_URL
    ) {
        $this->client = $client;
        $this->apiUserToken = $apiUserToken;
        $this->netacuityBaseUrl = $netacuityBaseUrl;
    }

    /**
     * @param string $ip The IP to fetch the result data set for.
     *
     * @return array The formatted data set.
     *
     * @throws NetacuityException On failure to send a Guzzle request.
     */
    public function fetch(string $ip)
    {
        $queryString = $this->buildQuery($this->apiUserToken, $ip);
        $request = new Request('GET', $queryString);

        $body = [];
        try {
            $response = $this->client->send($request);
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $this->handleGuzzleException($e);
        }

        return $this->parseBody($body);
    }

    /**
     * @param ClientException $e The thrown exception for handling.
     *
     * @throws NetacuityException A formatted exception masking the API User Token in the event that it becomes invalid.
     *
     * @return void
     */
    protected function handleGuzzleException(ClientException $e)
    {
        $response = $e->getResponse();
        $code = $response->getStatusCode();

        if ($code === 403) {
            throw new NetacuityException('NetAcuity API rejected the provided api user token.', $code);
        }

        $error = json_decode($response->getBody()->getContents(), true);
        $reason = Arrays::getNested($error, 'error.message');

        throw new NetacuityException("NetAcuity API rejected the request, Reason: {$reason}", $code);
    }

    /**
     * Parses the response into an array using the field definition.
     *
     * @param array $response The response from NetAcuity.
     *
     * @return array The response where the keys are from $fields and the values are from the $response.
     */
    protected function parseBody(array $response)
    {
        $responseData = Arrays::get($response, 'response');

        $result = [];
        Arrays::copyIfKeysExist($responseData, $result, $this->translations);

        return $result;
    }

    /**
     * @param string $userToken Net Acuity API User Token.
     * @param string $ip        The IP to be referenced in the lookup.
     *
     * @return string The formatted url query string.
     */
    protected function buildQuery(string $userToken, string $ip): string
    {
        return "{$this->netacuityBaseUrl}?u={$userToken}&dbs={$this->databaseIdentifier}&ip={$ip}&json=true";
    }
}
