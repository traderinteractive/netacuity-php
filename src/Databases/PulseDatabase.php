<?php

namespace TraderInteractive\NetAcuity\Databases;

use GuzzleHttp\ClientInterface;

class PulseDatabase extends AbstractNetAcuityDatabase
{
    /**
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

        $this->databaseIdentifier = 26;

        $this->translations = [
            'area-code' => 'pulse-area-codes',
            'city' => 'pulse-city',
            'city-code' => 'pulse-city-code',
            'city-conf' => 'pulse-city-conf',
            'conn-speed' => 'pulse-conn-speed',
            'conn-type' => 'pulse-conn-type',
            'continent-code' => 'pulse-continent-code',
            'country' => 'pulse-country',
            'country-code' => 'pulse-country-code',
            'country-conf' => 'pulse-country-conf',
            'county' => 'pulse-county',
            'gmt-offset' => 'pulse-gmt-offset',
            'in-dist' => 'pulse-in-dst',
            'ip' => 'ip',
            'latitude' => 'pulse-latitude',
            'longitude' => 'pulse-longitude',
            'metro-code' => 'pulse-metro-code',
            'postal-conf' => 'pulse-postal-conf',
            'region' => 'pulse-region',
            'region-code' => 'pulse-region-code',
            'region-conf' => 'pulse-region-conf',
            'timezone-name' => 'pulse-timezone-name',
            'transaction-id' => 'trans-id',
            'two-letter-country' => 'pulse-two-letter-country',
            'zip-code' => 'pulse-postal-code',
        ];
    }
}
