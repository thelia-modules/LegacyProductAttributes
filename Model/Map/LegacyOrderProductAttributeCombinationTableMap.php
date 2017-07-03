<?php

namespace LegacyProductAttributes\Model\Map;

use LegacyProductAttributes\Model\LegacyOrderProductAttributeCombination;
use LegacyProductAttributes\Model\LegacyOrderProductAttributeCombinationQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'legacy_order_product_attribute_combination' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LegacyOrderProductAttributeCombinationTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'LegacyProductAttributes.Model.Map.LegacyOrderProductAttributeCombinationTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'legacy_order_product_attribute_combination';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\LegacyProductAttributes\\Model\\LegacyOrderProductAttributeCombination';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'LegacyProductAttributes.Model.LegacyOrderProductAttributeCombination';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the ID field
     */
    const ID = 'legacy_order_product_attribute_combination.ID';

    /**
     * the column name for the ORDER_PRODUCT_ID field
     */
    const ORDER_PRODUCT_ID = 'legacy_order_product_attribute_combination.ORDER_PRODUCT_ID';

    /**
     * the column name for the PRODUCT_ID field
     */
    const PRODUCT_ID = 'legacy_order_product_attribute_combination.PRODUCT_ID';

    /**
     * the column name for the ATTRIBUTE_AV_ID field
     */
    const ATTRIBUTE_AV_ID = 'legacy_order_product_attribute_combination.ATTRIBUTE_AV_ID';

    /**
     * the column name for the QUANTITY field
     */
    const QUANTITY = 'legacy_order_product_attribute_combination.QUANTITY';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'OrderProductId', 'ProductId', 'AttributeAvId', 'Quantity', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'orderProductId', 'productId', 'attributeAvId', 'quantity', ),
        self::TYPE_COLNAME       => array(LegacyOrderProductAttributeCombinationTableMap::ID, LegacyOrderProductAttributeCombinationTableMap::ORDER_PRODUCT_ID, LegacyOrderProductAttributeCombinationTableMap::PRODUCT_ID, LegacyOrderProductAttributeCombinationTableMap::ATTRIBUTE_AV_ID, LegacyOrderProductAttributeCombinationTableMap::QUANTITY, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ORDER_PRODUCT_ID', 'PRODUCT_ID', 'ATTRIBUTE_AV_ID', 'QUANTITY', ),
        self::TYPE_FIELDNAME     => array('id', 'order_product_id', 'product_id', 'attribute_av_id', 'quantity', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OrderProductId' => 1, 'ProductId' => 2, 'AttributeAvId' => 3, 'Quantity' => 4, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'orderProductId' => 1, 'productId' => 2, 'attributeAvId' => 3, 'quantity' => 4, ),
        self::TYPE_COLNAME       => array(LegacyOrderProductAttributeCombinationTableMap::ID => 0, LegacyOrderProductAttributeCombinationTableMap::ORDER_PRODUCT_ID => 1, LegacyOrderProductAttributeCombinationTableMap::PRODUCT_ID => 2, LegacyOrderProductAttributeCombinationTableMap::ATTRIBUTE_AV_ID => 3, LegacyOrderProductAttributeCombinationTableMap::QUANTITY => 4, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ORDER_PRODUCT_ID' => 1, 'PRODUCT_ID' => 2, 'ATTRIBUTE_AV_ID' => 3, 'QUANTITY' => 4, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'order_product_id' => 1, 'product_id' => 2, 'attribute_av_id' => 3, 'quantity' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('legacy_order_product_attribute_combination');
        $this->setPhpName('LegacyOrderProductAttributeCombination');
        $this->setClassName('\\LegacyProductAttributes\\Model\\LegacyOrderProductAttributeCombination');
        $this->setPackage('LegacyProductAttributes.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('ORDER_PRODUCT_ID', 'OrderProductId', 'INTEGER', 'order_product', 'ID', true, null, null);
        $this->addColumn('PRODUCT_ID', 'ProductId', 'INTEGER', true, null, null);
        $this->addForeignKey('ATTRIBUTE_AV_ID', 'AttributeAvId', 'INTEGER', 'attribute_av', 'ID', true, null, null);
        $this->addColumn('QUANTITY', 'Quantity', 'INTEGER', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('OrderProduct', '\\Thelia\\Model\\OrderProduct', RelationMap::MANY_TO_ONE, array('order_product_id' => 'id', ), 'CASCADE', 'RESTRICT');
        $this->addRelation('AttributeAv', '\\Thelia\\Model\\AttributeAv', RelationMap::MANY_TO_ONE, array('attribute_av_id' => 'id', ), 'CASCADE', 'RESTRICT');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? LegacyOrderProductAttributeCombinationTableMap::CLASS_DEFAULT : LegacyOrderProductAttributeCombinationTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (LegacyOrderProductAttributeCombination object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LegacyOrderProductAttributeCombinationTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LegacyOrderProductAttributeCombinationTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LegacyOrderProductAttributeCombinationTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LegacyOrderProductAttributeCombinationTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LegacyOrderProductAttributeCombinationTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = LegacyOrderProductAttributeCombinationTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LegacyOrderProductAttributeCombinationTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LegacyOrderProductAttributeCombinationTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(LegacyOrderProductAttributeCombinationTableMap::ID);
            $criteria->addSelectColumn(LegacyOrderProductAttributeCombinationTableMap::ORDER_PRODUCT_ID);
            $criteria->addSelectColumn(LegacyOrderProductAttributeCombinationTableMap::PRODUCT_ID);
            $criteria->addSelectColumn(LegacyOrderProductAttributeCombinationTableMap::ATTRIBUTE_AV_ID);
            $criteria->addSelectColumn(LegacyOrderProductAttributeCombinationTableMap::QUANTITY);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ORDER_PRODUCT_ID');
            $criteria->addSelectColumn($alias . '.PRODUCT_ID');
            $criteria->addSelectColumn($alias . '.ATTRIBUTE_AV_ID');
            $criteria->addSelectColumn($alias . '.QUANTITY');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(LegacyOrderProductAttributeCombinationTableMap::DATABASE_NAME)->getTable(LegacyOrderProductAttributeCombinationTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(LegacyOrderProductAttributeCombinationTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(LegacyOrderProductAttributeCombinationTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new LegacyOrderProductAttributeCombinationTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a LegacyOrderProductAttributeCombination or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or LegacyOrderProductAttributeCombination object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyOrderProductAttributeCombinationTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \LegacyProductAttributes\Model\LegacyOrderProductAttributeCombination) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LegacyOrderProductAttributeCombinationTableMap::DATABASE_NAME);
            $criteria->add(LegacyOrderProductAttributeCombinationTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = LegacyOrderProductAttributeCombinationQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { LegacyOrderProductAttributeCombinationTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { LegacyOrderProductAttributeCombinationTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the legacy_order_product_attribute_combination table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LegacyOrderProductAttributeCombinationQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a LegacyOrderProductAttributeCombination or Criteria object.
     *
     * @param mixed               $criteria Criteria or LegacyOrderProductAttributeCombination object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyOrderProductAttributeCombinationTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from LegacyOrderProductAttributeCombination object
        }

        if ($criteria->containsKey(LegacyOrderProductAttributeCombinationTableMap::ID) && $criteria->keyContainsValue(LegacyOrderProductAttributeCombinationTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LegacyOrderProductAttributeCombinationTableMap::ID.')');
        }


        // Set the correct dbName
        $query = LegacyOrderProductAttributeCombinationQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // LegacyOrderProductAttributeCombinationTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LegacyOrderProductAttributeCombinationTableMap::buildTableMap();
