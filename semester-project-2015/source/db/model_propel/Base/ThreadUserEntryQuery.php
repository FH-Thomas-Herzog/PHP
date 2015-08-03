<?php

namespace Base;

use \ThreadUserEntry as ChildThreadUserEntry;
use \ThreadUserEntryQuery as ChildThreadUserEntryQuery;
use \Exception;
use \PDO;
use Map\ThreadUserEntryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'thread_user_entry' table.
 *
 * 
 *
 * @method     ChildThreadUserEntryQuery orderByThreadId($order = Criteria::ASC) Order by the thread_id column
 * @method     ChildThreadUserEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildThreadUserEntryQuery orderByFavoriteFlag($order = Criteria::ASC) Order by the favorite_flag column
 * @method     ChildThreadUserEntryQuery orderByCreatedDate($order = Criteria::ASC) Order by the created_date column
 *
 * @method     ChildThreadUserEntryQuery groupByThreadId() Group by the thread_id column
 * @method     ChildThreadUserEntryQuery groupByUserId() Group by the user_id column
 * @method     ChildThreadUserEntryQuery groupByFavoriteFlag() Group by the favorite_flag column
 * @method     ChildThreadUserEntryQuery groupByCreatedDate() Group by the created_date column
 *
 * @method     ChildThreadUserEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildThreadUserEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildThreadUserEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildThreadUserEntryQuery leftJoinThread($relationAlias = null) Adds a LEFT JOIN clause to the query using the Thread relation
 * @method     ChildThreadUserEntryQuery rightJoinThread($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Thread relation
 * @method     ChildThreadUserEntryQuery innerJoinThread($relationAlias = null) Adds a INNER JOIN clause to the query using the Thread relation
 *
 * @method     ChildThreadUserEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildThreadUserEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildThreadUserEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     \ThreadQuery|\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildThreadUserEntry findOne(ConnectionInterface $con = null) Return the first ChildThreadUserEntry matching the query
 * @method     ChildThreadUserEntry findOneOrCreate(ConnectionInterface $con = null) Return the first ChildThreadUserEntry matching the query, or a new ChildThreadUserEntry object populated from the query conditions when no match is found
 *
 * @method     ChildThreadUserEntry findOneByThreadId(int $thread_id) Return the first ChildThreadUserEntry filtered by the thread_id column
 * @method     ChildThreadUserEntry findOneByUserId(int $user_id) Return the first ChildThreadUserEntry filtered by the user_id column
 * @method     ChildThreadUserEntry findOneByFavoriteFlag(boolean $favorite_flag) Return the first ChildThreadUserEntry filtered by the favorite_flag column
 * @method     ChildThreadUserEntry findOneByCreatedDate(string $created_date) Return the first ChildThreadUserEntry filtered by the created_date column *

 * @method     ChildThreadUserEntry requirePk($key, ConnectionInterface $con = null) Return the ChildThreadUserEntry by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThreadUserEntry requireOne(ConnectionInterface $con = null) Return the first ChildThreadUserEntry matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildThreadUserEntry requireOneByThreadId(int $thread_id) Return the first ChildThreadUserEntry filtered by the thread_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThreadUserEntry requireOneByUserId(int $user_id) Return the first ChildThreadUserEntry filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThreadUserEntry requireOneByFavoriteFlag(boolean $favorite_flag) Return the first ChildThreadUserEntry filtered by the favorite_flag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThreadUserEntry requireOneByCreatedDate(string $created_date) Return the first ChildThreadUserEntry filtered by the created_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildThreadUserEntry[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildThreadUserEntry objects based on current ModelCriteria
 * @method     ChildThreadUserEntry[]|ObjectCollection findByThreadId(int $thread_id) Return ChildThreadUserEntry objects filtered by the thread_id column
 * @method     ChildThreadUserEntry[]|ObjectCollection findByUserId(int $user_id) Return ChildThreadUserEntry objects filtered by the user_id column
 * @method     ChildThreadUserEntry[]|ObjectCollection findByFavoriteFlag(boolean $favorite_flag) Return ChildThreadUserEntry objects filtered by the favorite_flag column
 * @method     ChildThreadUserEntry[]|ObjectCollection findByCreatedDate(string $created_date) Return ChildThreadUserEntry objects filtered by the created_date column
 * @method     ChildThreadUserEntry[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ThreadUserEntryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ThreadUserEntryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model_propel, e.g. 'Book'
     * @param     string $modelAlias The alias for the model_propel in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThreadUserEntry', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildThreadUserEntryQuery object.
     *
     * @param     string $modelAlias The alias of a model_propel in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildThreadUserEntryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildThreadUserEntryQuery) {
            return $criteria;
        }
        $query = new ChildThreadUserEntryQuery();
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
     * @param array[$thread_id, $user_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildThreadUserEntry|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ThreadUserEntryTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ThreadUserEntryTableMap::DATABASE_NAME);
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
     * @return ChildThreadUserEntry A model_propel object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT thread_id, user_id, favorite_flag, created_date FROM thread_user_entry WHERE thread_id = :p0 AND user_id = :p1';
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
            /** @var ChildThreadUserEntry $obj */
            $obj = new ChildThreadUserEntry();
            $obj->hydrate($row);
            ThreadUserEntryTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildThreadUserEntry|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ThreadUserEntryTableMap::COL_THREAD_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ThreadUserEntryTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the thread_id column
     *
     * Example usage:
     * <code>
     * $query->filterByThreadId(1234); // WHERE thread_id = 1234
     * $query->filterByThreadId(array(12, 34)); // WHERE thread_id IN (12, 34)
     * $query->filterByThreadId(array('min' => 12)); // WHERE thread_id > 12
     * </code>
     *
     * @see       filterByThread()
     *
     * @param     mixed $threadId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByThreadId($threadId = null, $comparison = null)
    {
        if (is_array($threadId)) {
            $useMinMax = false;
            if (isset($threadId['min'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $threadId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($threadId['max'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $threadId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $threadId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the favorite_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByFavoriteFlag(true); // WHERE favorite_flag = true
     * $query->filterByFavoriteFlag('yes'); // WHERE favorite_flag = true
     * </code>
     *
     * @param     boolean|string $favoriteFlag The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByFavoriteFlag($favoriteFlag = null, $comparison = null)
    {
        if (is_string($favoriteFlag)) {
            $favoriteFlag = in_array(strtolower($favoriteFlag), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ThreadUserEntryTableMap::COL_FAVORITE_FLAG, $favoriteFlag, $comparison);
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
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByCreatedDate($createdDate = null, $comparison = null)
    {
        if (is_array($createdDate)) {
            $useMinMax = false;
            if (isset($createdDate['min'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_CREATED_DATE, $createdDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdDate['max'])) {
                $this->addUsingAlias(ThreadUserEntryTableMap::COL_CREATED_DATE, $createdDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadUserEntryTableMap::COL_CREATED_DATE, $createdDate, $comparison);
    }

    /**
     * Filter the query by a related \Thread object
     *
     * @param \Thread|ObjectCollection $thread The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByThread($thread, $comparison = null)
    {
        if ($thread instanceof \Thread) {
            return $this
                ->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $thread->getId(), $comparison);
        } elseif ($thread instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ThreadUserEntryTableMap::COL_THREAD_ID, $thread->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByThread() only accepts arguments of type \Thread or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Thread relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function joinThread($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Thread');

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
            $this->addJoinObject($join, 'Thread');
        }

        return $this;
    }

    /**
     * Use the Thread relation Thread object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ThreadQuery A secondary query class using the current class as primary query
     */
    public function useThreadQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinThread($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Thread', '\ThreadQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ThreadUserEntryTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
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
     * @param   ChildThreadUserEntry $threadUserEntry Object to remove from the list of results
     *
     * @return $this|ChildThreadUserEntryQuery The current query, for fluid interface
     */
    public function prune($threadUserEntry = null)
    {
        if ($threadUserEntry) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ThreadUserEntryTableMap::COL_THREAD_ID), $threadUserEntry->getThreadId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ThreadUserEntryTableMap::COL_USER_ID), $threadUserEntry->getUserId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the thread_user_entry table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ThreadUserEntryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ThreadUserEntryTableMap::clearInstancePool();
            ThreadUserEntryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ThreadUserEntryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ThreadUserEntryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            ThreadUserEntryTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            ThreadUserEntryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ThreadUserEntryQuery
