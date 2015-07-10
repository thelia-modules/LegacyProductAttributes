<?php

namespace LegacyProductAttributes;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class LegacyProductAttributes extends BaseModule
{
    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
    }
}
