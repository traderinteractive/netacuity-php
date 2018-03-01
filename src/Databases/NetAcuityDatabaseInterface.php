<?php

namespace TraderInteractive\NetAcuity\Databases;

/**
 * The Net Acuity Database Interface
 */
interface NetAcuityDatabaseInterface
{
    /**
     * @param string $ip The IP to fetch the data from.
     *
     * @return array The formatted array fetched from the Net Acuity database.
     */
    public function fetch(string $ip);
}
