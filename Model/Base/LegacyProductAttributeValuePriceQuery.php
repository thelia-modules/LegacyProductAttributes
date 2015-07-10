<?php

namespace LegacyProductAttributes\Model\Base;

use \Exception;
use \PDO;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePrice as ChildLegacyProductAttributeValuePrice;
use LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery as ChildLegacyProductAttributeValuePriceQuery;
use LegacyProductAttributes\Model\Map\LegacyProductAttributeValuePriceTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\AttributeAv;
use Thelia\Model\Currency;
use Thelia\Model\Product;

/**
 * Base class that represents a query for the 'legacy_product_attribute_value_price' table.
 *
 *
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery orderByProductId($order = Criteria::ASC) Order by the product_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery orderByAttributeAvId($order = Criteria::ASC) Order by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery orderByCurrencyId($order = Criteria::ASC) Order by the currency_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery orderByDelta($order = Criteria::ASC) Order by the delta column
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery groupByProductId() Group by the product_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery groupByAttributeAvId() Group by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery groupByCurrencyId() Group by the currency_id column
 * @method     ChildLegacyProductAttributeValuePriceQuery groupByDelta() Group by the delta column
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLegacyProductAttributeValuePriceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLegacyProductAttributeValuePriceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery leftJoinProduct($relationAlias = null) Adds a LEFT JOIN clause to the query using the Product relation
 * @method     ChildLegacyProductAttributeValuePriceQuery rightJoinProduct($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Product relation
 * @method     ChildLegacyProductAttributeValuePriceQuery innerJoinProduct($relationAlias = null) Adds a INNER JOIN clause to the query using the Product relation
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery leftJoinAttributeAv($relationAlias = null) Adds a LEFT JOIN clause to the query using the AttributeAv relation
 * @method     ChildLegacyProductAttributeValuePriceQuery rightJoinAttributeAv($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AttributeAv relation
 * @method     ChildLegacyProductAttributeValuePriceQuery innerJoinAttributeAv($relationAlias = null) Adds a INNER JOIN clause to the query using the AttributeAv relation
 *
 * @method     ChildLegacyProductAttributeValuePriceQuery leftJoinCurrency($relationAlias = null) Adds a LEFT JOIN clause to the query using the Currency relation
 * @method     ChildLegacyProductAttributeValuePriceQuery rightJoinCurrency($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Currency relation
 * @method     ChildLegacyProductAttributeValuePriceQuery innerJoinCurrency($relationAlias = null) Adds a INNER JOIN clause to the query using the Currency relation
 *
 * @method     ChildLegacyProductAttributeValuePrice findOne(ConnectionInterface $con = null) Return the first ChildLegacyProductAttributeValuePrice matching the query
 * @method     ChildLegacyProductAttributeValuePrice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLegacyProductAttributeValuePrice matching the query, or a new ChildLegacyProductAttributeValuePrice object populated from the query conditions when no match is found
 *
 * @method     ChildLegacyProductAttributeValuePrice findOneByProductId(int $product_id) Return the first ChildLegacyProductAttributeValuePrice filtered by the product_id column
 * @method     ChildLegacyProductAttributeValuePrice findOneByAttributeAvId(int $attribute_av_id) Return the first ChildLegacyProductAttributeValuePrice filtered by the attribute_av_id column
 * @method     ChildLegacyProductAttributeValuePrice findOneByCurrencyId(int $currency_id) Return the first ChildLegacyProductAttributeValuePrice filtered by the currency_id column
 * @method     ChildLegacyProductAttributeValuePrice findOneByDelta(double $delta) Return the first ChildLegacyProductAttributeValuePrice filtered by the delta column
 *
 * @method     array findByProductId(int $product_id) Return ChildLegacyProductAttributeValuePrice objects filtered by the product_id column
 * @method     array findByAttributeAvId(int $attribute_av_id) Return ChildLegacyProductAttributeValuePrice objects filtered by the attribute_av_id column
 * @method     array findByCurrencyId(int $currency_id) Return ChildLegacyProductAttributeValuePrice objects filtered by the currency_id column
 * @method     array findByDelta(double $delta) Return ChildLegacyProductAttributeValuePrice objects filtered by the delta column
 *
 */
abstract class LegacyProductAttributeValuePriceQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \LegacyProductAttributes\Model\Base\LegacyProductAttributeValuePriceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\LegacyProductAttributes\\Model\\LegacyProductAttributeValuePrice', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLegacyProductAttributeValuePriceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLegacyProductAttributeValuePriceQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery) {
            return $criteria;
        }
        $query = new \LegacyProductAttributes\Model\LegacyProductAttributeValuePriceQuery();
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
     * $obj = $c->findPk(array(12, 34, 56), $con);
     * </code>
     *
     * @param array[$product_id, $attribute_av_id, $currency_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLegacyProductAttributeValuePrice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LegacyProductAttributeValuePriceTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1], (string) $key[2]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
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
     * @return   ChildLegacyProductAttributeValuePrice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT PRODUCT_ID, ATTRIBUTE_AV_ID, CURRENCY_ID, DELTA FROM legacy_product_attribute_value_price WHERE PRODUCT_ID = :p0 AND ATTRIBUTE_AV_ID = :p1 AND CURRENCY_ID = :p2';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->bindValue(':p2', $key[2], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildLegacyProductAttributeValuePrice();
            $obj->hydrate($row);
            LegacyProductAttributeValuePriceTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1], (string) $key[2])));
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
     * @return ChildLegacyProductAttributeValuePrice|array|mixed the result, formatted by the current formatter
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $key[1], Criteria::EQUAL);
        $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $key[2], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $cton2 = $this->getNewCriterion(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $key[2], Criteria::EQUAL);
            $cton0->addAnd($cton2);
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByProductId($productId = null, $comparison = null)
    {
        if (is_array($productId)) {
            $useMinMax = false;
            if (isset($productId['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $productId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($productId['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $productId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $productId, $comparison);
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByAttributeAvId($attributeAvId = null, $comparison = null)
    {
        if (is_array($attributeAvId)) {
            $useMinMax = false;
            if (isset($attributeAvId['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $attributeAvId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($attributeAvId['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $attributeAvId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $attributeAvId, $comparison);
    }

    /**
     * Filter the query on the currency_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrencyId(1234); // WHERE currency_id = 1234
     * $query->filterByCurrencyId(array(12, 34)); // WHERE currency_id IN (12, 34)
     * $query->filterByCurrencyId(array('min' => 12)); // WHERE currency_id > 12
     * </code>
     *
     * @see       filterByCurrency()
     *
     * @param     mixed $currencyId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByCurrencyId($currencyId = null, $comparison = null)
    {
        if (is_array($currencyId)) {
            $useMinMax = false;
            if (isset($currencyId['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $currencyId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($currencyId['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $currencyId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $currencyId, $comparison);
    }

    /**
     * Filter the query on the delta column
     *
     * Example usage:
     * <code>
     * $query->filterByDelta(1234); // WHERE delta = 1234
     * $query->filterByDelta(array(12, 34)); // WHERE delta IN (12, 34)
     * $query->filterByDelta(array('min' => 12)); // WHERE delta > 12
     * </code>
     *
     * @param     mixed $delta The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByDelta($delta = null, $comparison = null)
    {
        if (is_array($delta)) {
            $useMinMax = false;
            if (isset($delta['min'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::DELTA, $delta['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($delta['max'])) {
                $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::DELTA, $delta['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LegacyProductAttributeValuePriceTableMap::DELTA, $delta, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Product object
     *
     * @param \Thelia\Model\Product|ObjectCollection $product The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByProduct($product, $comparison = null)
    {
        if ($product instanceof \Thelia\Model\Product) {
            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $product->getId(), $comparison);
        } elseif ($product instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID, $product->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByAttributeAv($attributeAv, $comparison = null)
    {
        if ($attributeAv instanceof \Thelia\Model\AttributeAv) {
            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $attributeAv->getId(), $comparison);
        } elseif ($attributeAv instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID, $attributeAv->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
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
     * Filter the query by a related \Thelia\Model\Currency object
     *
     * @param \Thelia\Model\Currency|ObjectCollection $currency The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function filterByCurrency($currency, $comparison = null)
    {
        if ($currency instanceof \Thelia\Model\Currency) {
            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $currency->getId(), $comparison);
        } elseif ($currency instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID, $currency->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCurrency() only accepts arguments of type \Thelia\Model\Currency or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Currency relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function joinCurrency($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Currency');

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
            $this->addJoinObject($join, 'Currency');
        }

        return $this;
    }

    /**
     * Use the Currency relation Currency object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\CurrencyQuery A secondary query class using the current class as primary query
     */
    public function useCurrencyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCurrency($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Currency', '\Thelia\Model\CurrencyQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLegacyProductAttributeValuePrice $legacyProductAttributeValuePrice Object to remove from the list of results
     *
     * @return ChildLegacyProductAttributeValuePriceQuery The current query, for fluid interface
     */
    public function prune($legacyProductAttributeValuePrice = null)
    {
        if ($legacyProductAttributeValuePrice) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LegacyProductAttributeValuePriceTableMap::PRODUCT_ID), $legacyProductAttributeValuePrice->getProductId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LegacyProductAttributeValuePriceTableMap::ATTRIBUTE_AV_ID), $legacyProductAttributeValuePrice->getAttributeAvId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond2', $this->getAliasedColName(LegacyProductAttributeValuePriceTableMap::CURRENCY_ID), $legacyProductAttributeValuePrice->getCurrencyId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1', 'pruneCond2'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the legacy_product_attribute_value_price table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
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
            LegacyProductAttributeValuePriceTableMap::clearInstancePool();
            LegacyProductAttributeValuePriceTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildLegacyProductAttributeValuePrice or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildLegacyProductAttributeValuePrice object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LegacyProductAttributeValuePriceTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        LegacyProductAttributeValuePriceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LegacyProductAttributeValuePriceTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // LegacyProductAttributeValuePriceQuery
