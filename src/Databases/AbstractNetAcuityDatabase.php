<?php

namespace DominionEnterprises\NetAcuity\Databases;

use DominionEnterprises\Util\Arrays;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * The Abstract NetAcuityDatabase used as a base for each database model.
 */
abstract class AbstractNetAcuityDatabase implements NetAcuityDatabaseInterface
{
    /**
     * @var Client The GuzzleHttp Client.
     */
    protected $_client;

    /**
     * @var array The translations array for the data set.
     */
    protected $_translations;

    /**
     * @var string The API User Token.
     */
    protected $_apiUserToken;

    /**
     * @var int The Net Acuity Database ID.
     */
    protected $_databaseIdentifier;

    /**
     * AbstractNetAcuityDatabase constructor.
     *
     * @param ClientInterface $client       The injected GuzzleHttp Client.
     * @param string          $apiUserToken The Net Acuity API User Token.
     */
    public function __construct(
        ClientInterface $client,
        string $apiUserToken
    )
    {
        $this->_client = $client;
        $this->_apiUserToken = $apiUserToken;
    }

    /**
     * @param string $ip The IP to fetch the result data set for.
     *
     * @return array The formatted data set.
     *
     * @throws Exception On failure to send a Guzzle request.
     */
    public function fetch(string $ip)
    {
        $queryString = $this->_buildQuery($this->_apiUserToken, $ip);
        $request = new Request('GET', $queryString);

        $body = [];
        try {
            $response = $this->_client->send($request);
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $this->_handleGuzzleException($e);
        }

        return $this->_parseBody($body);
    }

    /**
     * @param ClientException $e The thrown exception for handling.
     *
     * @throws Exception A formatted exception masking the API User Token in the event that it becomes invalid.
     *
     * @return void
     */
    protected function _handleGuzzleException(ClientException $e)
    {
        $response = $e->getResponse();
        $code = $response->getStatusCode();

        if ($code === 403) {
            throw new Exception('NetAcuity API rejected the provided api user token.', $code);
        }

        $error = json_decode($response->getBody()->getContents(), true);
        $reason = Arrays::getNested($error, 'error.message');

        throw new Exception("NetAcuity API rejected the request, Reason: {$reason}", $code);
    }

    /**
     * Parses the response into an array using the field definition.
     *
     * @param array $response The response from NetAcuity.
     *
     * @return array The response where the keys are from $fields and the values are from the $response.
     */
    protected function _parseBody(array $response)
    {
        $responseData = Arrays::get($response, 'response');

        $result = [];
        Arrays::copyIfKeysExist($responseData, $result, $this->_translations);

        return $result;
    }

    /**
     * @param string $userToken Net Acuity API User Token.
     * @param string $ip        The IP to be referenced in the lookup.
     *
     * @return string The formatted url query string.
     */
    protected function _buildQuery(string $userToken, string $ip) : string
    {
        $baseUrl = 'https://usa.cloud.netacuity.com/webservice/query';
        return "{$baseUrl}?u={$userToken}&dbs={$this->_databaseIdentifier}&ip={$ip}&json=true";
    }
}
