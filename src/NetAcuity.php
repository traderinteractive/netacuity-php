<?php
namespace DominionEnterprises\NetAcuity;

use DominionEnterprises\Util;
use Socket\Raw\Socket;

/**
 * A client to access a NetAcuity server for geoip lookup.
 */
final class NetAcuity
{
    /** @type \Socket\Raw\Socket The NetAcuity socket. */
    private $_socket;

    /** @type int The API Id identifying your client. */
    private $_apiId;

    /**
     * Create the NetAcuity client.
     *
     * @param \Socket\Raw\Socket $socket The NetAcuity socket.
     * @param int $apiId The API Id identifying your client.
     */
    public function __construct(Socket $socket, $apiId)
    {
        Util::throwIfNotType(['int' => $apiId]);

        $this->_socket = $socket;
        $this->_apiId = $apiId;
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
    public function getGeo($ip)
    {
        Util::throwIfNotType(['string' => $ip], true);

        $response = $this->_query($this->_buildQuery(4, $ip));
        return $this->_parseResponse(
            $response,
            [
                'country',
                'region',
                'city',
                'conn-speed',
                'metro-code',
                'latitude',
                'longitude',
                'zip-code',
                'country-code',
                'region-code',
                'city-code',
                'continent-code',
                'two-letter-country',
                'internal-code',
                'area-code',
                'country-conf',
                'region-conf',
                'city-conf',
                'postal-conf',
                'gmt-offset',
                'in-dist',
                'timezone-name',
            ]
        );
    }

    /**
     * Builds the query to NetAcuity.
     *
     * @param int $databaseId The database id to query.
     * @param string $ip The ip address to lookup.
     *
     * @return string The formatted query string.
     */
    private function _buildQuery($databaseId, $ip)
    {
        Util::throwIfNotType(['string' => $ip, 'int' => $databaseId], true);

        return sprintf("%d;%d;%s\r\n", $databaseId, $this->_apiId, $ip);
    }

    /**
     * Executes the query against NetAcuity and returns the unformatted response.
     *
     * Failures will be raised as exceptions.
     *
     * @param string $query The formatted query string.
     *
     * @return string The response from NetAcuity.
     */
    private function _query($query)
    {
        Util::throwIfNotType(['string' => $query], true);

        $this->_socket->write($query);

        // Remove the first 4 bytes (size data) and the last 3 bytes (standard footer)
        $response = substr($this->_socket->read(1024), 4, -3);

        return $response;
    }

    /**
     * Parses the response into an array using the field definition.
     *
     * @param string $response The response from NetAcuity.
     * @param array $fields The expected fields in the response.
     *
     * @return array The response where the keys are from $fields and the values are from the $response.
     */
    private function _parseResponse($response, array $fields)
    {
        Util::throwIfNotType(['string' => $response], true);
        Util::throwIfNotType(['string' => $fields], true);

        $responseData = explode(';', $response);
        Util::ensure(count($fields), count($responseData));

        return array_combine($fields, $responseData);
    }
}
