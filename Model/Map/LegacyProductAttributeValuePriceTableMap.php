<?php

namespace LegacyProductAttributes\Model\Map;

use LegacyProductAttributes\Model\LegacyProductAttributeValuePrice;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery;
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
 * This class defines the structure of the 'legacy_product_attribute_value_price' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LegacyProductAttributeValuePriceTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'LegacyProductAttributes.Model.Map.LegacyProductAttributeValuePriceTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'legacy_product_attribute_value_price';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\LegacyProductAttributes\\Model\\LegacyProductAttributeValuePrice';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'LegacyProductAttributes.Model.LegacyProductAttributeValuePrice';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 4;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 4;

    /**
     * the column name for the PRODUCT_ID field
     */
    const PRODUCT_ID = 'legacy_product_attribute_value_price.PRODUCT_ID';

    /**
     * the column name for the ATTRIBUTE_AV_ID field
     */
    const ATTRIBUTE_AV_ID = 'legacy_product_attribute_value_price.ATTRIBUTE_AV_ID';

    /**
     * the column name for the CURRENCY_ID field
     */
    const CURRENCY_ID = 'legacy_product_attribute_value_price.CURRENCY_ID';

    /**
     * the column name for the DELTA field
     */
    const DELTA = 'legacy_product_attribute_value_price.DELTA';

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
        self::TYPE_PHPNAME       => array('ProductId', 'AttributeAvId', 'CurrencyId', 'Delta', ),
        self::TYPE_STUDLYPHPNAME => array('productId', 'attributeAvId', 'currencyId', 'delta', ),
        self::TYPE_COLNAME       => array(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, LegacyProductAttributeValuePriceTableMap::DELTA, ),
        self::TYPE_RAW_COLNAME   => array('PRODUCT_ID', 'ATTRIBUTE_AV_ID', 'CURRENCY_ID', 'DELTA', ),
        self::TYPE_FIELDNAME     => array('product_id', 'attribute_av_id', 'currency_id', 'delta', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ProductId' => 0, 'AttributeAvId' => 1, 'CurrencyId' => 2, 'Delta' => 3, ),
        self::TYPE_STUDLYPHPNAME => array('productId' => 0, 'attributeAvId' => 1, 'currencyId' => 2, 'delta' => 3, ),
        self::TYPE_COLNAME       => array(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID => 0, LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID => 1, LegacyProductAttributeValuePriceTableMap::CURRENCY_ID => 2, LegacyProductAttributeValuePriceTableMap::DELTA => 3, ),
        self::TYPE_RAW_COLNAME   => array('PRODUCT_ID' => 0, 'ATTRIBUTE_AV_ID' => 1, 'CURRENCY_ID' => 2, 'DELTA' => 3, ),
        self::TYPE_FIELDNAME     => array('product_id' => 0, 'attribute_av_id' => 1, 'currency_id' => 2, 'delta' => 3, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, )
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
        $this->setName('legacy_product_attribute_value_price');
        $this->setPhpName('LegacyProductAttributeValuePrice');
        $this->setClassName('\\LegacyProductAttributes\\Model\\LegacyProductAttributeValuePrice');
        $this->setPackage('LegacyProductAttributes.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('PRODUCT_ID', 'ProductId', 'INTEGER' , 'product', 'ID', true, null, null);
        $this->addForeignPrimaryKey('ATTRIBUTE_AV_ID', 'AttributeAvId', 'INTEGER' , 'attribute_av', 'ID', true, null, null);
        $this->addForeignPrimaryKey('CURRENCY_ID', 'CurrencyId', 'INTEGER' , 'currency', 'ID', true, null, null);
        $this->addColumn('DELTA', 'Delta', 'DECIMAL', false, 16, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Product', '\\Thelia\\Model\\Product', RelationMap::MANY_TO_ONE, array('product_id' => 'id', ), 'CASCADE', 'RESTRICT');
        $this->addRelation('AttributeAv', '\\Thelia\\Model\\AttributeAv', RelationMap::MANY_TO_ONE, array('attribute_av_id' => 'id', ), 'CASCADE', 'RESTRICT');
        $this->addRelation('Currency', '\\Thelia\\Model\\Currency', RelationMap::MANY_TO_ONE, array('currency_id' => 'id', ), 'CASCADE', 'RESTRICT');
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice $obj A \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getProductId(), (string) $obj->getAttributeAvId(), (string) $obj->getCurrencyId()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice) {
                $key = serialize(array((string) $value->getProductId(), (string) $value->getAttributeAvId(), (string) $value->getCurrencyId()));

            } elseif (is_array($value) && count($value) === 3) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1], (string) $value[2]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProductId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AttributeAvId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('CurrencyId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProductId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('AttributeAvId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('CurrencyId', TableMap::TYPE_PHPNAME, $indexType)]));
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

            return $pks;
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
        return $withPrefix ? LegacyProductAttributeValuePriceTableMap::CLASS_DEFAULT : LegacyProductAttributeValuePriceTableMap::OM_CLASS;
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
     * @return array (LegacyProductAttributeValuePrice object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LegacyProductAttributeValuePriceTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LegacyProductAttributeValuePriceTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LegacyProductAttributeValuePriceTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LegacyProductAttributeValuePriceTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LegacyProductAttributeValuePriceTableMap::addInstanceToPool($obj, $key);
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
            $key = LegacyProductAttributeValuePriceTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LegacyProductAttributeValuePriceTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LegacyProductAttributeValuePriceTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID);
            $criteria->addSelectColumn(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID);
            $criteria->addSelectColumn(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID);
            $criteria->addSelectColumn(LegacyProductAttributeValuePriceTableMap::DELTA);
        } else {
            $criteria->addSelectColumn($alias . '.PRODUCT_ID');
            $criteria->addSelectColumn($alias . '.ATTRIBUTE_AV_ID');
            $criteria->addSelectColumn($alias . '.CURRENCY_ID');
            $criteria->addSelectColumn($alias . '.DELTA');
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
        return Propel::getServiceContainer()->getDatabaseMap(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME)->getTable(LegacyProductAttributeValuePriceTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(LegacyProductAttributeValuePriceTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new LegacyProductAttributeValuePriceTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a LegacyProductAttributeValuePrice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or LegacyProductAttributeValuePrice object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \LegacyProductAttributes\Model\LegacyProductAttributeValuePrice) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $value[1]));
                $criterion->addAnd($criteria->getNewCriterion(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $value[2]));
                $criteria->addOr($criterion);
            }
        }

        $query = LegacyProductAttributeValuePriceQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { LegacyProductAttributeValuePriceTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { LegacyProductAttributeValuePriceTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the legacy_product_attribute_value_price table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LegacyProductAttributeValuePriceQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a LegacyProductAttributeValuePrice or Criteria object.
     *
     * @param mixed               $criteria Criteria or LegacyProductAttributeValuePrice object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from LegacyProductAttributeValuePrice object
        }


        // Set the correct dbName
        $query = LegacyProductAttributeValuePriceQuery::create()->mergeWith($criteria);

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

} // LegacyProductAttributeValuePriceTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LegacyProductAttributeValuePriceTableMap::buildTableMap();
