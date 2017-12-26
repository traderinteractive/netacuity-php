<?php
namespace DominionEnterprises\NetAcuity;

use DominionEnterprises\Util;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * A client to access a NetAcuity server for geoip lookup.
 */
final class NetAcuity
{
    /**
     * @var string Net Acuity's Edge DB Code.
     */
    const NETACUITY_EDGE_DB_ID = 4;

    /**
     * A Collection of implemented DBs referred to by the NetAcuity DB Id.
     */
    const IMPLEMENTED_DBS = [
        self::NETACUITY_EDGE_DB_ID
    ];

    /**
     * @var string The API User token provided by NetAcuity.
     */
    private $_apiUserToken;

    /**
     * @var Client The Guzzle Client to be used.
     */
    private $_client;

    /**
     * @var string The NetAcuity database to fetch data from.
     */
    private $_dbId;

    /**
     * Create the NetAcuity client.
     *
     * @param ClientInterface $client       The guzzle client to be used. Needs no configuration from the calling application.
     * @param string          $apiUserToken The passed in API User Token specific to the calling application or organization.
     * @param string          $dbIdentifier The netacuity database identifier, ex: NetAcuity::NETACUITY_EDGE_DB_ID
     */
    public function __construct(ClientInterface $client, string $apiUserToken, string $dbIdentifier)
    {
        if (!in_array($dbIdentifier, self::IMPLEMENTED_DBS)) {
            throw new Exception("NetAcuity DB Identifier: {$dbIdentifier} not yet supported by this tool.");
        }

        $this->_client = $client;
        $this->_apiUserToken = $apiUserToken;
        $this->_dbId = $dbIdentifier;
    }

    /**
     * Gets the geo data for the given IP.
     *
     * Failures will be raised as exceptions.
     *
     * @param string $ip The ip address to lookup
     *
     * @return array {
     *     @type string $country
     *     @type string $region
     *     @type string $city
     *     @type string $conn-speed
     *     @type string $metro-code
     *     @type string $latitude
     *     @type string $longitude
     *     @type string $postal-code
     *     @type string $country-code
     *     @type string $region-code
     *     @type string $city-code
     *     @type string $continent-code
     *     @type string $two-letter-country
     *     @type string $internal-code
     *     @type string $area-code
     *     @type string $country-conf
     *     @type string $region-conf
     *     @type string $city-conf
     *     @type string $postal-conf
     *     @type string $gmt-offset
     *     @type string $in-dist
     *     @type string $timezone-name
     * }
     */
    public function getGeo(string $ip)
    {
        Util::throwIfNotType(['string' => $ip], true);

        $queryString = $this->_buildQuery($this->_apiUserToken, self::NETACUITY_EDGE_DB_ID, $ip);
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

    private function _buildQuery(string $userToken, string $db, string $ip, bool $asJson = true) : string
    {
        $baseUrl = 'https://usa.cloud.netacuity.com/webservice/query';
        $asJsonString = $asJson ? 'true' : 'false';
        return "{$baseUrl}?u={$userToken}&dbs={$db}&ip={$ip}&json={$asJsonString}";
    }

    /**
     * @param ClientException $e The thrown exception for handling.
     *
     * @throws Exception A formatted exception masking the API User Token in the event that it becomes invalid.
     */
    private function _handleGuzzleException(ClientException $e)
    {
        $response = $e->getResponse();
        $code = $response->getStatusCode();

        if ($code === 403) {
            throw new Exception('NetAcuity API rejected the provided api user token.', $code);
        }

        $error = json_decode($response->getBody()->getContents(), true);
        $reason = Util\Arrays::getNested($error, 'error.message');

        throw new Exception("NetAcuity API rejected the request, Reason: {$reason}", $code);
    }

    /**
     * Parses the response into an array using the field definition.
     *
     * @param array $response The response from NetAcuity.
     *
     * @return array The response where the keys are from $fields and the values are from the $response.
     * @throws Exception
     */
    private function _parseBody(array $response)
    {
        $responseData = Util\Arrays::get($response, 'response');
        $edgeTranslations = [
            'area-code' => 'edge-area-codes',
            'city' => 'edge-city',
            'city-code' => 'edge-city-code',
            'city-conf' => 'edge-city-conf',
            'conn-speed' => 'edge-conn-speed',
            'continent-code' => 'edge-continent-code',
            'country' => 'edge-country',
            'country-code' => 'edge-country-code',
            'country-conf' => 'edge-country-conf',
            'gmt-offset' => 'edge-gmt-offset',
            'in-dist' => 'edge-in-dst',
            'latitude' => 'edge-latitude',
            'longitude' => 'edge-longitude',
            'metro-code' => 'edge-metro-code',
            'postal-conf' => 'edge-postal-conf',
            'region' => 'edge-region',
            'region-code' => 'edge-region-code',
            'region-conf' => 'edge-region-conf',
            'timezone-name' => 'edge-timezone-name',
            'two-letter-country' => 'edge-two-letter-country',
            'zip-code' => 'edge-postal-code',
        ];

        $result = [];
        Util\Arrays::copyIfKeysExist($responseData, $result, $edgeTranslations);

        return $result;
    }
}
