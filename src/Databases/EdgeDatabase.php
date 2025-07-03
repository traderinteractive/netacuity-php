<?php

namespace TraderInteractive\NetAcuity\Databases;

use GuzzleHttp\ClientInterface;

/**
 * Formats and returns our expected data set according to the Net Acuity Edge type database.
 */
final class EdgeDatabase extends AbstractNetAcuityDatabase
{
    /**
     * EdgeDatabase constructor.
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
        parent::__construct($client, $apiUserToken, $netacuityBaseUrl);

        $this->databaseIdentifier = 4;

        $this->translations = [
            'area-code' => 'edge-area-codes',
            'city' => 'edge-city',
            'city-code' => 'edge-city-code',
            'city-conf' => 'edge-city-conf',
            'conn-speed' => 'edge-conn-speed',
            'continent-code' => 'edge-continent-code',
            'country' => 'edge-country',
            'country-code' => 'edge-country-code',
            'country-conf' => 'edge-country-conf',
            'county' => 'edge-county',
            'gmt-offset' => 'edge-gmt-offset',
            'in-dist' => 'edge-in-dst',
            'ip' => 'ip',
            'latitude' => 'edge-latitude',
            'longitude' => 'edge-longitude',
            'metro-code' => 'edge-metro-code',
            'postal-conf' => 'edge-postal-conf',
            'region' => 'edge-region',
            'region-code' => 'edge-region-code',
            'region-conf' => 'edge-region-conf',
            'timezone-name' => 'edge-timezone-name',
            'transaction-id' => 'trans-id',
            'two-letter-country' => 'edge-two-letter-country',
            'zip-code' => 'edge-postal-code',
        ];
    }
}
