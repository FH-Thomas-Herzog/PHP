<?php

namespace Base;

use \Locale as ChildLocale;
use \LocaleQuery as ChildLocaleQuery;
use \Exception;
use \PDO;
use Map\LocaleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'locale' table.
 *
 * 
 *
 * @method     ChildLocaleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLocaleQuery orderByCreatedDate($order = Criteria::ASC) Order by the created_date column
 * @method     ChildLocaleQuery orderByUpdatedDate($order = Criteria::ASC) Order by the updated_date column
 * @method     ChildLocaleQuery orderByResourceKey($order = Criteria::ASC) Order by the resource_key column
 *
 * @method     ChildLocaleQuery groupById() Group by the id column
 * @method     ChildLocaleQuery groupByCreatedDate() Group by the created_date column
 * @method     ChildLocaleQuery groupByUpdatedDate() Group by the updated_date column
 * @method     ChildLocaleQuery groupByResourceKey() Group by the resource_key column
 *
 * @method     ChildLocaleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLocaleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLocaleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLocaleQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildLocaleQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildLocaleQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     \UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLocale findOne(ConnectionInterface $con = null) Return the first ChildLocale matching the query
 * @method     ChildLocale findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLocale matching the query, or a new ChildLocale object populated from the query conditions when no match is found
 *
 * @method     ChildLocale findOneById(string $id) Return the first ChildLocale filtered by the id column
 * @method     ChildLocale findOneByCreatedDate(string $created_date) Return the first ChildLocale filtered by the created_date column
 * @method     ChildLocale findOneByUpdatedDate(string $updated_date) Return the first ChildLocale filtered by the updated_date column
 * @method     ChildLocale findOneByResourceKey(string $resource_key) Return the first ChildLocale filtered by the resource_key column *

 * @method     ChildLocale requirePk($key, ConnectionInterface $con = null) Return the ChildLocale by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocale requireOne(ConnectionInterface $con = null) Return the first ChildLocale matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocale requireOneById(string $id) Return the first ChildLocale filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocale requireOneByCreatedDate(string $created_date) Return the first ChildLocale filtered by the created_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocale requireOneByUpdatedDate(string $updated_date) Return the first ChildLocale filtered by the updated_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLocale requireOneByResourceKey(string $resource_key) Return the first ChildLocale filtered by the resource_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLocale[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLocale objects based on current ModelCriteria
 * @method     ChildLocale[]|ObjectCollection findById(string $id) Return ChildLocale objects filtered by the id column
 * @method     ChildLocale[]|ObjectCollection findByCreatedDate(string $created_date) Return ChildLocale objects filtered by the created_date column
 * @method     ChildLocale[]|ObjectCollection findByUpdatedDate(string $updated_date) Return ChildLocale objects filtered by the updated_date column
 * @method     ChildLocale[]|ObjectCollection findByResourceKey(string $resource_key) Return ChildLocale objects filtered by the resource_key column
 * @method     ChildLocale[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LocaleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\LocaleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model_propel, e.g. 'Book'
     * @param     string $modelAlias The alias for the model_propel in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Locale', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLocaleQuery object.
     *
     * @param     string $modelAlias The alias of a model_propel in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLocaleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLocaleQuery) {
            return $criteria;
        }
        $query = new ChildLocaleQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLocale|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LocaleTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LocaleTableMap::DATABASE_NAME);
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
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLocale A model_propel object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, created_date, updated_date, resource_key FROM locale WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLocale $obj */
            $obj = new ChildLocale();
            $obj->hydrate($row);
            LocaleTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildLocale|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
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
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LocaleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LocaleTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById('fooValue');   // WHERE id = 'fooValue'
     * $query->filterById('%fooValue%'); // WHERE id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $id The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $id)) {
                $id = str_replace('*', '%', $id);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LocaleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the created_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedDate('2011-03-14'); // WHERE created_date = '2011-03-14'
     * $query->filterByCreatedDate('now'); // WHERE created_date = '2011-03-14'
     * $query->filterByCreatedDate(array('max' => 'yesterday')); // WHERE created_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByCreatedDate($createdDate = null, $comparison = null)
    {
        if (is_array($createdDate)) {
            $useMinMax = false;
            if (isset($createdDate['min'])) {
                $this->addUsingAlias(LocaleTableMap::COL_CREATED_DATE, $createdDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdDate['max'])) {
                $this->addUsingAlias(LocaleTableMap::COL_CREATED_DATE, $createdDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocaleTableMap::COL_CREATED_DATE, $createdDate, $comparison);
    }

    /**
     * Filter the query on the updated_date column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedDate('2011-03-14'); // WHERE updated_date = '2011-03-14'
     * $query->filterByUpdatedDate('now'); // WHERE updated_date = '2011-03-14'
     * $query->filterByUpdatedDate(array('max' => 'yesterday')); // WHERE updated_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByUpdatedDate($updatedDate = null, $comparison = null)
    {
        if (is_array($updatedDate)) {
            $useMinMax = false;
            if (isset($updatedDate['min'])) {
                $this->addUsingAlias(LocaleTableMap::COL_UPDATED_DATE, $updatedDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedDate['max'])) {
                $this->addUsingAlias(LocaleTableMap::COL_UPDATED_DATE, $updatedDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LocaleTableMap::COL_UPDATED_DATE, $updatedDate, $comparison);
    }

    /**
     * Filter the query on the resource_key column
     *
     * Example usage:
     * <code>
     * $query->filterByResourceKey('fooValue');   // WHERE resource_key = 'fooValue'
     * $query->filterByResourceKey('%fooValue%'); // WHERE resource_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $resourceKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByResourceKey($resourceKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($resourceKey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $resourceKey)) {
                $resourceKey = str_replace('*', '%', $resourceKey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LocaleTableMap::COL_RESOURCE_KEY, $resourceKey, $comparison);
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLocaleQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(LocaleTableMap::COL_ID, $user->getLocaleId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLocale $locale Object to remove from the list of results
     *
     * @return $this|ChildLocaleQuery The current query, for fluid interface
     */
    public function prune($locale = null)
    {
        if ($locale) {
            $this->addUsingAlias(LocaleTableMap::COL_ID, $locale->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the locale table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocaleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LocaleTableMap::clearInstancePool();
            LocaleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LocaleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LocaleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            LocaleTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            LocaleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LocaleQuery
