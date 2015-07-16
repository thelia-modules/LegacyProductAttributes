<?php

namespace LegacyProductAttributes;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class LegacyProductAttributes extends BaseModule
{
    const MESSAGE_DOMAIN = 'legacyproductattributes';
    const MESSAGE_DOMAIN_BO = 'legacyproductattributes.bo.default';
    const MESSAGE_DOMAIN_FO = 'legacyproductattributes.fo.default';

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
    }
}
