<?php

namespace TraderInteractive\NetAcuity;

/**
 * Contract for NetAcuity api client.
 */
interface NetAcuityInterface
{
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
    public function getGeo(string $ip) : array;
}
