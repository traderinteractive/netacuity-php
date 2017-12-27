<?php

namespace DominionEnterprises\NetAcuity\Tests;

use DominionEnterprises\NetAcuity\Databases\NetAcuityDatabaseInterface;

class TestDatabase implements NetAcuityDatabaseInterface
{
    public function fetch(string $ip)
    {
        return ['some' => 'data'];
    }
}
