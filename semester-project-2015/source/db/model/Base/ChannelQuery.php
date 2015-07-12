<?php

namespace Base;

use \Channel as ChildChannel;
use \ChannelQuery as ChildChannelQuery;
use \Exception;
use \PDO;
use Map\ChannelTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'channel' table.
 *
 * 
 *
 * @method     ChildChannelQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChannelQuery orderByCreationDate($order = Criteria::ASC) Order by the creation_date column
 * @method     ChildChannelQuery orderByUpdatedDate($order = Criteria::ASC) Order by the updated_date column
 * @method     ChildChannelQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildChannelQuery orderByDescription($order = Criteria::ASC) Order by the description column
 *
 * @method     ChildChannelQuery groupById() Group by the id column
 * @method     ChildChannelQuery groupByCreationDate() Group by the creation_date column
 * @method     ChildChannelQuery groupByUpdatedDate() Group by the updated_date column
 * @method     ChildChannelQuery groupByTitle() Group by the title column
 * @method     ChildChannelQuery groupByDescription() Group by the description column
 *
 * @method     ChildChannelQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChannelQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChannelQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChannelQuery leftJoinChannelUserEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChannelUserEntry relation
 * @method     ChildChannelQuery rightJoinChannelUserEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChannelUserEntry relation
 * @method     ChildChannelQuery innerJoinChannelUserEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the ChannelUserEntry relation
 *
 * @method     ChildChannelQuery leftJoinThread($relationAlias = null) Adds a LEFT JOIN clause to the query using the Thread relation
 * @method     ChildChannelQuery rightJoinThread($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Thread relation
 * @method     ChildChannelQuery innerJoinThread($relationAlias = null) Adds a INNER JOIN clause to the query using the Thread relation
 *
 * @method     \ChannelUserEntryQuery|\ThreadQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildChannel findOne(ConnectionInterface $con = null) Return the first ChildChannel matching the query
 * @method     ChildChannel findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChannel matching the query, or a new ChildChannel object populated from the query conditions when no match is found
 *
 * @method     ChildChannel findOneById(int $id) Return the first ChildChannel filtered by the id column
 * @method     ChildChannel findOneByCreationDate(string $creation_date) Return the first ChildChannel filtered by the creation_date column
 * @method     ChildChannel findOneByUpdatedDate(string $updated_date) Return the first ChildChannel filtered by the updated_date column
 * @method     ChildChannel findOneByTitle(string $title) Return the first ChildChannel filtered by the title column
 * @method     ChildChannel findOneByDescription(string $description) Return the first ChildChannel filtered by the description column *

 * @method     ChildChannel requirePk($key, ConnectionInterface $con = null) Return the ChildChannel by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOne(ConnectionInterface $con = null) Return the first ChildChannel matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChannel requireOneById(int $id) Return the first ChildChannel filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByCreationDate(string $creation_date) Return the first ChildChannel filtered by the creation_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByUpdatedDate(string $updated_date) Return the first ChildChannel filtered by the updated_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByTitle(string $title) Return the first ChildChannel filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByDescription(string $description) Return the first ChildChannel filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChannel[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildChannel objects based on current ModelCriteria
 * @method     ChildChannel[]|ObjectCollection findById(int $id) Return ChildChannel objects filtered by the id column
 * @method     ChildChannel[]|ObjectCollection findByCreationDate(string $creation_date) Return ChildChannel objects filtered by the creation_date column
 * @method     ChildChannel[]|ObjectCollection findByUpdatedDate(string $updated_date) Return ChildChannel objects filtered by the updated_date column
 * @method     ChildChannel[]|ObjectCollection findByTitle(string $title) Return ChildChannel objects filtered by the title column
 * @method     ChildChannel[]|ObjectCollection findByDescription(string $description) Return ChildChannel objects filtered by the description column
 * @method     ChildChannel[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ChannelQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\ChannelQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Channel', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChannelQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChannelQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildChannelQuery) {
            return $criteria;
        }
        $query = new ChildChannelQuery();
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
     * @return ChildChannel|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ChannelTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChannelTableMap::DATABASE_NAME);
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
     * @return ChildChannel A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, creation_date, updated_date, title, description FROM channel WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildChannel $obj */
            $obj = new ChildChannel();
            $obj->hydrate($row);
            ChannelTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildChannel|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChannelTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChannelTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the creation_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationDate('2011-03-14'); // WHERE creation_date = '2011-03-14'
     * $query->filterByCreationDate('now'); // WHERE creation_date = '2011-03-14'
     * $query->filterByCreationDate(array('max' => 'yesterday')); // WHERE creation_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $creationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByCreationDate($creationDate = null, $comparison = null)
    {
        if (is_array($creationDate)) {
            $useMinMax = false;
            if (isset($creationDate['min'])) {
                $this->addUsingAlias(ChannelTableMap::COL_CREATION_DATE, $creationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creationDate['max'])) {
                $this->addUsingAlias(ChannelTableMap::COL_CREATION_DATE, $creationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_CREATION_DATE, $creationDate, $comparison);
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByUpdatedDate($updatedDate = null, $comparison = null)
    {
        if (is_array($updatedDate)) {
            $useMinMax = false;
            if (isset($updatedDate['min'])) {
                $this->addUsingAlias(ChannelTableMap::COL_UPDATED_DATE, $updatedDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedDate['max'])) {
                $this->addUsingAlias(ChannelTableMap::COL_UPDATED_DATE, $updatedDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_UPDATED_DATE, $updatedDate, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query by a related \ChannelUserEntry object
     *
     * @param \ChannelUserEntry|ObjectCollection $channelUserEntry the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChannelQuery The current query, for fluid interface
     */
    public function filterByChannelUserEntry($channelUserEntry, $comparison = null)
    {
        if ($channelUserEntry instanceof \ChannelUserEntry) {
            return $this
                ->addUsingAlias(ChannelTableMap::COL_ID, $channelUserEntry->getChannelId(), $comparison);
        } elseif ($channelUserEntry instanceof ObjectCollection) {
            return $this
                ->useChannelUserEntryQuery()
                ->filterByPrimaryKeys($channelUserEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChannelUserEntry() only accepts arguments of type \ChannelUserEntry or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChannelUserEntry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function joinChannelUserEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChannelUserEntry');

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
            $this->addJoinObject($join, 'ChannelUserEntry');
        }

        return $this;
    }

    /**
     * Use the ChannelUserEntry relation ChannelUserEntry object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \ChannelUserEntryQuery A secondary query class using the current class as primary query
     */
    public function useChannelUserEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChannelUserEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChannelUserEntry', '\ChannelUserEntryQuery');
    }

    /**
     * Filter the query by a related \Thread object
     *
     * @param \Thread|ObjectCollection $thread the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChannelQuery The current query, for fluid interface
     */
    public function filterByThread($thread, $comparison = null)
    {
        if ($thread instanceof \Thread) {
            return $this
                ->addUsingAlias(ChannelTableMap::COL_ID, $thread->getChannelId(), $comparison);
        } elseif ($thread instanceof ObjectCollection) {
            return $this
                ->useThreadQuery()
                ->filterByPrimaryKeys($thread->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildChannel $channel Object to remove from the list of results
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function prune($channel = null)
    {
        if ($channel) {
            $this->addUsingAlias(ChannelTableMap::COL_ID, $channel->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the channel table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChannelTableMap::clearInstancePool();
            ChannelTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChannelTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            ChannelTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            ChannelTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ChannelQuery
