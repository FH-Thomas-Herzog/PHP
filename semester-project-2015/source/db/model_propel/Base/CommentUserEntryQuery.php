<?php

namespace Base;

use \CommentUserEntry as ChildCommentUserEntry;
use \CommentUserEntryQuery as ChildCommentUserEntryQuery;
use \Exception;
use \PDO;
use Map\CommentUserEntryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'comment_user_entry' table.
 *
 * 
 *
 * @method     ChildCommentUserEntryQuery orderByCommentId($order = Criteria::ASC) Order by the comment_id column
 * @method     ChildCommentUserEntryQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildCommentUserEntryQuery orderByCreatedDate($order = Criteria::ASC) Order by the created_date column
 * @method     ChildCommentUserEntryQuery orderByUpdatedDate($order = Criteria::ASC) Order by the updated_date column
 * @method     ChildCommentUserEntryQuery orderByImportantFlag($order = Criteria::ASC) Order by the important_flag column
 * @method     ChildCommentUserEntryQuery orderByReadFlag($order = Criteria::ASC) Order by the read_flag column
 *
 * @method     ChildCommentUserEntryQuery groupByCommentId() Group by the comment_id column
 * @method     ChildCommentUserEntryQuery groupByUserId() Group by the user_id column
 * @method     ChildCommentUserEntryQuery groupByCreatedDate() Group by the created_date column
 * @method     ChildCommentUserEntryQuery groupByUpdatedDate() Group by the updated_date column
 * @method     ChildCommentUserEntryQuery groupByImportantFlag() Group by the important_flag column
 * @method     ChildCommentUserEntryQuery groupByReadFlag() Group by the read_flag column
 *
 * @method     ChildCommentUserEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCommentUserEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCommentUserEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCommentUserEntryQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildCommentUserEntryQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildCommentUserEntryQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildCommentUserEntryQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildCommentUserEntryQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildCommentUserEntryQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     \CommentQuery|\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCommentUserEntry findOne(ConnectionInterface $con = null) Return the first ChildCommentUserEntry matching the query
 * @method     ChildCommentUserEntry findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCommentUserEntry matching the query, or a new ChildCommentUserEntry object populated from the query conditions when no match is found
 *
 * @method     ChildCommentUserEntry findOneByCommentId(int $comment_id) Return the first ChildCommentUserEntry filtered by the comment_id column
 * @method     ChildCommentUserEntry findOneByUserId(int $user_id) Return the first ChildCommentUserEntry filtered by the user_id column
 * @method     ChildCommentUserEntry findOneByCreatedDate(string $created_date) Return the first ChildCommentUserEntry filtered by the created_date column
 * @method     ChildCommentUserEntry findOneByUpdatedDate(string $updated_date) Return the first ChildCommentUserEntry filtered by the updated_date column
 * @method     ChildCommentUserEntry findOneByImportantFlag(boolean $important_flag) Return the first ChildCommentUserEntry filtered by the important_flag column
 * @method     ChildCommentUserEntry findOneByReadFlag(boolean $read_flag) Return the first ChildCommentUserEntry filtered by the read_flag column *

 * @method     ChildCommentUserEntry requirePk($key, ConnectionInterface $con = null) Return the ChildCommentUserEntry by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOne(ConnectionInterface $con = null) Return the first ChildCommentUserEntry matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCommentUserEntry requireOneByCommentId(int $comment_id) Return the first ChildCommentUserEntry filtered by the comment_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOneByUserId(int $user_id) Return the first ChildCommentUserEntry filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOneByCreatedDate(string $created_date) Return the first ChildCommentUserEntry filtered by the created_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOneByUpdatedDate(string $updated_date) Return the first ChildCommentUserEntry filtered by the updated_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOneByImportantFlag(boolean $important_flag) Return the first ChildCommentUserEntry filtered by the important_flag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCommentUserEntry requireOneByReadFlag(boolean $read_flag) Return the first ChildCommentUserEntry filtered by the read_flag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCommentUserEntry[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCommentUserEntry objects based on current ModelCriteria
 * @method     ChildCommentUserEntry[]|ObjectCollection findByCommentId(int $comment_id) Return ChildCommentUserEntry objects filtered by the comment_id column
 * @method     ChildCommentUserEntry[]|ObjectCollection findByUserId(int $user_id) Return ChildCommentUserEntry objects filtered by the user_id column
 * @method     ChildCommentUserEntry[]|ObjectCollection findByCreatedDate(string $created_date) Return ChildCommentUserEntry objects filtered by the created_date column
 * @method     ChildCommentUserEntry[]|ObjectCollection findByUpdatedDate(string $updated_date) Return ChildCommentUserEntry objects filtered by the updated_date column
 * @method     ChildCommentUserEntry[]|ObjectCollection findByImportantFlag(boolean $important_flag) Return ChildCommentUserEntry objects filtered by the important_flag column
 * @method     ChildCommentUserEntry[]|ObjectCollection findByReadFlag(boolean $read_flag) Return ChildCommentUserEntry objects filtered by the read_flag column
 * @method     ChildCommentUserEntry[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CommentUserEntryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CommentUserEntryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model_propel, e.g. 'Book'
     * @param     string $modelAlias The alias for the model_propel in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\CommentUserEntry', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCommentUserEntryQuery object.
     *
     * @param     string $modelAlias The alias of a model_propel in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCommentUserEntryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCommentUserEntryQuery) {
            return $criteria;
        }
        $query = new ChildCommentUserEntryQuery();
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
     * @param array[$comment_id, $user_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCommentUserEntry|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CommentUserEntryTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CommentUserEntryTableMap::DATABASE_NAME);
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
     * @return ChildCommentUserEntry A model_propel object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT comment_id, user_id, created_date, updated_date, important_flag, read_flag FROM comment_user_entry WHERE comment_id = :p0 AND user_id = :p1';
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
            /** @var ChildCommentUserEntry $obj */
            $obj = new ChildCommentUserEntry();
            $obj->hydrate($row);
            CommentUserEntryTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildCommentUserEntry|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(CommentUserEntryTableMap::COL_COMMENT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(CommentUserEntryTableMap::COL_USER_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the comment_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCommentId(1234); // WHERE comment_id = 1234
     * $query->filterByCommentId(array(12, 34)); // WHERE comment_id IN (12, 34)
     * $query->filterByCommentId(array('min' => 12)); // WHERE comment_id > 12
     * </code>
     *
     * @see       filterByComment()
     *
     * @param     mixed $commentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByCommentId($commentId = null, $comparison = null)
    {
        if (is_array($commentId)) {
            $useMinMax = false;
            if (isset($commentId['min'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $commentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($commentId['max'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $commentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $commentId, $comparison);
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
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByCreatedDate($createdDate = null, $comparison = null)
    {
        if (is_array($createdDate)) {
            $useMinMax = false;
            if (isset($createdDate['min'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_CREATED_DATE, $createdDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdDate['max'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_CREATED_DATE, $createdDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_CREATED_DATE, $createdDate, $comparison);
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
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByUpdatedDate($updatedDate = null, $comparison = null)
    {
        if (is_array($updatedDate)) {
            $useMinMax = false;
            if (isset($updatedDate['min'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_UPDATED_DATE, $updatedDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedDate['max'])) {
                $this->addUsingAlias(CommentUserEntryTableMap::COL_UPDATED_DATE, $updatedDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_UPDATED_DATE, $updatedDate, $comparison);
    }

    /**
     * Filter the query on the important_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByImportantFlag(true); // WHERE important_flag = true
     * $query->filterByImportantFlag('yes'); // WHERE important_flag = true
     * </code>
     *
     * @param     boolean|string $importantFlag The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByImportantFlag($importantFlag = null, $comparison = null)
    {
        if (is_string($importantFlag)) {
            $importantFlag = in_array(strtolower($importantFlag), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_IMPORTANT_FLAG, $importantFlag, $comparison);
    }

    /**
     * Filter the query on the read_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByReadFlag(true); // WHERE read_flag = true
     * $query->filterByReadFlag('yes'); // WHERE read_flag = true
     * </code>
     *
     * @param     boolean|string $readFlag The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByReadFlag($readFlag = null, $comparison = null)
    {
        if (is_string($readFlag)) {
            $readFlag = in_array(strtolower($readFlag), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CommentUserEntryTableMap::COL_READ_FLAG, $readFlag, $comparison);
    }

    /**
     * Filter the query by a related \Comment object
     *
     * @param \Comment|ObjectCollection $comment The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \Comment) {
            return $this
                ->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $comment->getId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CommentUserEntryTableMap::COL_COMMENT_ID, $comment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

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
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\CommentQuery');
    }

    /**
     * Filter the query by a related \User object
     *
     * @param \User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CommentUserEntryTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
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
     * @param   ChildCommentUserEntry $commentUserEntry Object to remove from the list of results
     *
     * @return $this|ChildCommentUserEntryQuery The current query, for fluid interface
     */
    public function prune($commentUserEntry = null)
    {
        if ($commentUserEntry) {
            $this->addCond('pruneCond0', $this->getAliasedColName(CommentUserEntryTableMap::COL_COMMENT_ID), $commentUserEntry->getCommentId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(CommentUserEntryTableMap::COL_USER_ID), $commentUserEntry->getUserId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the comment_user_entry table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentUserEntryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CommentUserEntryTableMap::clearInstancePool();
            CommentUserEntryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CommentUserEntryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CommentUserEntryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CommentUserEntryTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CommentUserEntryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CommentUserEntryQuery
