<?php

namespace LegacyProductAttributes\Model\Base;

use \Exception;
use \PDO;
use LegacyProductAttributes\Model\LegacyProductAttributeValue as ChildLegacyProductAttributeValue;
use LegacyProductAttributes\Model\LegacyProductAttributeValueQuery as ChildLegacyProductAttributeValueQuery;
use LegacyProductAttributes\Model\Map\LegacyProductAttributeValueTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\AttributeAv;
use Thelia\Model\Product;

/**
 * Base class that represents a query for the 'legacy_product_attribute_value' table.
 *
 *
 *
 * @method     ChildLegacyProductAttributeValueQuery orderByProductId($order = Criteria::ASC) Order by the product_id column
 * @method     ChildLegacyProductAttributeValueQuery orderByAttributeAvId($order = Criteria::ASC) Order by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValueQuery orderByWeightDelta($order = Criteria::ASC) Order by the weight_delta column
 * @method     ChildLegacyProductAttributeValueQuery orderByStock($order = Criteria::ASC) Order by the stock column
 * @method     ChildLegacyProductAttributeValueQuery orderByVisible($order = Criteria::ASC) Order by the visible column
 *
 * @method     ChildLegacyProductAttributeValueQuery groupByProductId() Group by the product_id column
 * @method     ChildLegacyProductAttributeValueQuery groupByAttributeAvId() Group by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValueQuery groupByWeightDelta() Group by the weight_delta column
 * @method     ChildLegacyProductAttributeValueQuery groupByStock() Group by the stock column
 * @method     ChildLegacyProductAttributeValueQuery groupByVisible() Group by the visible column
 *
 * @method     ChildLegacyProductAttributeValueQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLegacyProductAttributeValueQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLegacyProductAttributeValueQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLegacyProductAttributeValueQuery leftJoinProduct($relationAlias = null) Adds a LEFT JOIN clause to the query using the Product relation
 * @method     ChildLegacyProductAttributeValueQuery rightJoinProduct($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Product relation
 * @method     ChildLegacyProductAttributeValueQuery innerJoinProduct($relationAlias = null) Adds a INNER JOIN clause to the query using the Product relation
 *
 * @method     ChildLegacyProductAttributeValueQuery leftJoinAttributeAv($relationAlias = null) Adds a LEFT JOIN clause to the query using the AttributeAv relation
 * @method     ChildLegacyProductAttributeValueQuery rightJoinAttributeAv($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AttributeAv relation
 * @method     ChildLegacyProductAttributeValueQuery innerJoinAttributeAv($relationAlias = null) Adds a INNER JOIN clause to the query using the AttributeAv relation
 *
 * @method     ChildLegacyProductAttributeValue findOne(ConnectionInterface $con = null) Return the first ChildLegacyProductAttributeValue matching the query
 * @method     ChildLegacyProductAttributeValue findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLegacyProductAttributeValue matching the query, or a new ChildLegacyProductAttributeValue object populated from the query conditions when no match is found
 *
 * @method     ChildLegacyProductAttributeValue findOneByProductId(int $product_id) Return the first ChildLegacyProductAttributeValue filtered by the product_id column
 * @method     ChildLegacyProductAttributeValue findOneByAttributeAvId(int $attribute_av_id) Return the first ChildLegacyProductAttributeValue filtered by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValue findOneByWeightDelta(double $weight_delta) Return the first ChildLegacyProductAttributeValue filtered by the weight_delta column
 * @method     ChildLegacyProductAttributeValue findOneByStock(int $stock) Return the first ChildLegacyProductAttributeValue filtered by the stock column
 * @method     ChildLegacyProductAttributeValue findOneByVisible(boolean $visible) Return the first ChildLegacyProductAttributeValue filtered by the visible column
 *
 * @method     array findByProductId(int $product_id) Return ChildLegacyProductAttributeValue objects filtered by the product_id column
 * @method     array findByAttributeAvId(int $attribute_av_id) Return ChildLegacyProductAttributeValue objects filtered by the attribute_av_id column
 * @method     array findByWeightDelta(double $weight_delta) Return ChildLegacyProductAttributeValue objects filtered by the weight_delta column
 * @method     array findByStock(int $stock) Return ChildLegacyProductAttributeValue objects filtered by the stock column
 * @method     array findByVisible(boolean $visible) Return ChildLegacyProductAttributeValue objects filtered by the visible column
 *
 */
abstract class LegacyProductAttributeValueQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \LegacyProductAttributes\Model\Base\LegacyProductAttributeValueQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\LegacyProductAttributes\\Model\\LegacyProductAttributeValue', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLegacyProductAttributeValueQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLegacyProductAttributeValueQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \LegacyProductAttributes\Model\LegacyProductAttributeValueQuery) {
            return $criteria;
        }
        $query = new \LegacyProductAttributes\Model\LegacyProductAttributeValueQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$product_id, $attribute_av_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLegacyProductAttributeValue|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LegacyProductAttributeValueTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LegacyProductAttributeValueTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildLegacyProductAttributeValue A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT PRODUCT_ID, ATTRIBUTE_AV_ID, WEIGHT_DELTA, STOCK, VISIBLE FROM legacy_product_attribute_value WHERE PRODUCT_ID = :p0 AND ATTRIBUTE_AV_ID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildLegacyProductAttributeValue();
            $obj->hydrate($row);
            LegacyProductAttributeValueTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildLegacyProductAttributeValue|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LegacyProductAttributeValueTableMap::PRODUCT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the product_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProductId(1234); // WHERE product_id = 1234
     * $query->filterByProductId(array(12, 34)); // WHERE product_id IN (12, 34)
     * $query->filterByProductId(array('min' => 12)); // WHERE product_id > 12
     * </code>
     *
     * @see       filterByProduct()
     *
     * @param     mixed $productId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByProductId($productId = null, $comparison = null)
    {
        if (is_array($productId)) {
            $useMinMax = false;
            if (isset($productId['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $productId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($productId['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $productId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $productId, $comparison);
    }

    /**
     * Filter the query on the attribute_av_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAttributeAvId(1234); // WHERE attribute_av_id = 1234
     * $query->filterByAttributeAvId(array(12, 34)); // WHERE attribute_av_id IN (12, 34)
     * $query->filterByAttributeAvId(array('min' => 12)); // WHERE attribute_av_id > 12
     * </code>
     *
     * @see       filterByAttributeAv()
     *
     * @param     mixed $attributeAvId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByAttributeAvId($attributeAvId = null, $comparison = null)
    {
        if (is_array($attributeAvId)) {
            $useMinMax = false;
            if (isset($attributeAvId['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $attributeAvId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($attributeAvId['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $attributeAvId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $attributeAvId, $comparison);
    }

    /**
     * Filter the query on the weight_delta column
     *
     * Example usage:
     * <code>
     * $query->filterByWeightDelta(1234); // WHERE weight_delta = 1234
     * $query->filterByWeightDelta(array(12, 34)); // WHERE weight_delta IN (12, 34)
     * $query->filterByWeightDelta(array('min' => 12)); // WHERE weight_delta > 12
     * </code>
     *
     * @param     mixed $weightDelta The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByWeightDelta($weightDelta = null, $comparison = null)
    {
        if (is_array($weightDelta)) {
            $useMinMax = false;
            if (isset($weightDelta['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::WEIGHT_DELTA, $weightDelta['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weightDelta['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::WEIGHT_DELTA, $weightDelta['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValueTableMap::WEIGHT_DELTA, $weightDelta, $comparison);
    }

    /**
     * Filter the query on the stock column
     *
     * Example usage:
     * <code>
     * $query->filterByStock(1234); // WHERE stock = 1234
     * $query->filterByStock(array(12, 34)); // WHERE stock IN (12, 34)
     * $query->filterByStock(array('min' => 12)); // WHERE stock > 12
     * </code>
     *
     * @param     mixed $stock The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByStock($stock = null, $comparison = null)
    {
        if (is_array($stock)) {
            $useMinMax = false;
            if (isset($stock['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::STOCK, $stock['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stock['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValueTableMap::STOCK, $stock['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValueTableMap::STOCK, $stock, $comparison);
    }

    /**
     * Filter the query on the visible column
     *
     * Example usage:
     * <code>
     * $query->filterByVisible(true); // WHERE visible = true
     * $query->filterByVisible('yes'); // WHERE visible = true
     * </code>
     *
     * @param     boolean|string $visible The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByVisible($visible = null, $comparison = null)
    {
        if (is_string($visible)) {
            $visible = in_array(strtolower($visible), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LegacyProductAttributeValueTableMap::VISIBLE, $visible, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Product object
     *
     * @param \Thelia\Model\Product|ObjectCollection $product The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByProduct($product, $comparison = null)
    {
        if ($product instanceof \Thelia\Model\Product) {
            return $this
                ->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $product->getId(), $comparison);
        } elseif ($product instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegacyProductAttributeValueTableMap::PRODUCT_ID, $product->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProduct() only accepts arguments of type \Thelia\Model\Product or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Product relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function joinProduct($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Product');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Product');
        }

        return $this;
    }

    /**
     * Use the Product relation Product object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\ProductQuery A secondary query class using the current class as primary query
     */
    public function useProductQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinProduct($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Product', '\Thelia\Model\ProductQuery');
    }

    /**
     * Filter the query by a related \Thelia\Model\AttributeAv object
     *
     * @param \Thelia\Model\AttributeAv|ObjectCollection $attributeAv The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function filterByAttributeAv($attributeAv, $comparison = null)
    {
        if ($attributeAv instanceof \Thelia\Model\AttributeAv) {
            return $this
                ->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $attributeAv->getId(), $comparison);
        } elseif ($attributeAv instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID, $attributeAv->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAttributeAv() only accepts arguments of type \Thelia\Model\AttributeAv or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AttributeAv relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function joinAttributeAv($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AttributeAv');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'AttributeAv');
        }

        return $this;
    }

    /**
     * Use the AttributeAv relation AttributeAv object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\AttributeAvQuery A secondary query class using the current class as primary query
     */
    public function useAttributeAvQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAttributeAv($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AttributeAv', '\Thelia\Model\AttributeAvQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLegacyProductAttributeValue $legacyProductAttributeValue Object to remove from the list of results
     *
     * @return ChildLegacyProductAttributeValueQuery The current query, for fluid interface
     */
    public function prune($legacyProductAttributeValue = null)
    {
        if ($legacyProductAttributeValue) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LegacyProductAttributeValueTableMap::PRODUCT_ID), $legacyProductAttributeValue->getProductId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LegacyProductAttributeValueTableMap::ATTRIBUTE_AV_ID), $legacyProductAttributeValue->getAttributeAvId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the legacy_product_attribute_value table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValueTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LegacyProductAttributeValueTableMap::clearInstancePool();
            LegacyProductAttributeValueTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildLegacyProductAttributeValue or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildLegacyProductAttributeValue object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValueTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LegacyProductAttributeValueTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        LegacyProductAttributeValueTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LegacyProductAttributeValueTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // LegacyProductAttributeValueQuery
