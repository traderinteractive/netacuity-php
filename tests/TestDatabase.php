<?php

namespace TraderInteractive\NetAcuity\Tests;

use TraderInteractive\NetAcuity\Databases\NetAcuityDatabaseInterface;

class TestDatabase implements NetAcuityDatabaseInterface
{
    public function fetch(string $ip)
    {
        return ['some' => 'data'];
    }
}
