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

    public function preActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);

        $database->insertSql(null, [__DIR__ . "/Config/create.sql"]);
    }

    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false)
    {
        if (!$deleteModuleData) {
            return;
        }

        $database = new Database($con);

        $database->insertSql(null, [__DIR__ . "/Config/delete.sql"]);
    }
}
