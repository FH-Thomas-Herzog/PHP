<?php

namespace Base;

use \Comment as ChildComment;
use \CommentQuery as ChildCommentQuery;
use \Exception;
use \PDO;
use Map\CommentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'comment' table.
 *
 * 
 *
 * @method     ChildCommentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCommentQuery orderByCreatedDate($order = Criteria::ASC) Order by the created_date column
 * @method     ChildCommentQuery orderByUpdatedDate($order = Criteria::ASC) Order by the updated_date column
 * @method     ChildCommentQuery orderByUserComment($order = Criteria::ASC) Order by the user_comment column
 * @method     ChildCommentQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildCommentQuery orderByThemeId($order = Criteria::ASC) Order by the theme_id column
 * @method     ChildCommentQuery orderByThreadId($order = Criteria::ASC) Order by the thread_id column
 *
 * @method     ChildCommentQuery groupById() Group by the id column
 * @method     ChildCommentQuery groupByCreatedDate() Group by the created_date column
 * @method     ChildCommentQuery groupByUpdatedDate() Group by the updated_date column
 * @method     ChildCommentQuery groupByUserComment() Group by the user_comment column
 * @method     ChildCommentQuery groupByUserId() Group by the user_id column
 * @method     ChildCommentQuery groupByThemeId() Group by the theme_id column
 * @method     ChildCommentQuery groupByThreadId() Group by the thread_id column
 *
 * @method     ChildCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCommentQuery leftJoinCommentRelatedByThreadId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CommentRelatedByThreadId relation
 * @method     ChildCommentQuery rightJoinCommentRelatedByThreadId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CommentRelatedByThreadId relation
 * @method     ChildCommentQuery innerJoinCommentRelatedByThreadId($relationAlias = null) Adds a INNER JOIN clause to the query using the CommentRelatedByThreadId relation
 *
 * @method     ChildCommentQuery leftJoinThread($relationAlias = null) Adds a LEFT JOIN clause to the query using the Thread relation
 * @method     ChildCommentQuery rightJoinThread($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Thread relation
 * @method     ChildCommentQuery innerJoinThread($relationAlias = null) Adds a INNER JOIN clause to the query using the Thread relation
 *
 * @method     ChildCommentQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildCommentQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildCommentQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildCommentQuery leftJoinCommentRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the CommentRelatedById relation
 * @method     ChildCommentQuery rightJoinCommentRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CommentRelatedById relation
 * @method     ChildCommentQuery innerJoinCommentRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the CommentRelatedById relation
 *
 * @method     ChildCommentQuery leftJoinCommentUserEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the CommentUserEntry relation
 * @method     ChildCommentQuery rightJoinCommentUserEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CommentUserEntry relation
 * @method     ChildCommentQuery innerJoinCommentUserEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the CommentUserEntry relation
 *
 * @method     \CommentQuery|\ThreadQuery|\UserQuery|\CommentUserEntryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildComment findOne(ConnectionInterface $con = null) Return the first ChildComment matching the query
 * @method     ChildComment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildComment matching the query, or a new ChildComment object populated from the query conditions when no match is found
 *
 * @method     ChildComment findOneById(int $id) Return the first ChildComment filtered by the id column
 * @method     ChildComment findOneByCreatedDate(string $created_date) Return the first ChildComment filtered by the created_date column
 * @method     ChildComment findOneByUpdatedDate(string $updated_date) Return the first ChildComment filtered by the updated_date column
 * @method     ChildComment findOneByUserComment(string $user_comment) Return the first ChildComment filtered by the user_comment column
 * @method     ChildComment findOneByUserId(int $user_id) Return the first ChildComment filtered by the user_id column
 * @method     ChildComment findOneByThemeId(int $theme_id) Return the first ChildComment filtered by the theme_id column
 * @method     ChildComment findOneByThreadId(int $thread_id) Return the first ChildComment filtered by the thread_id column *

 * @method     ChildComment requirePk($key, ConnectionInterface $con = null) Return the ChildComment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOne(ConnectionInterface $con = null) Return the first ChildComment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildComment requireOneById(int $id) Return the first ChildComment filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByCreatedDate(string $created_date) Return the first ChildComment filtered by the created_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByUpdatedDate(string $updated_date) Return the first ChildComment filtered by the updated_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByUserComment(string $user_comment) Return the first ChildComment filtered by the user_comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByUserId(int $user_id) Return the first ChildComment filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByThemeId(int $theme_id) Return the first ChildComment filtered by the theme_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildComment requireOneByThreadId(int $thread_id) Return the first ChildComment filtered by the thread_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildComment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildComment objects based on current ModelCriteria
 * @method     ChildComment[]|ObjectCollection findById(int $id) Return ChildComment objects filtered by the id column
 * @method     ChildComment[]|ObjectCollection findByCreatedDate(string $created_date) Return ChildComment objects filtered by the created_date column
 * @method     ChildComment[]|ObjectCollection findByUpdatedDate(string $updated_date) Return ChildComment objects filtered by the updated_date column
 * @method     ChildComment[]|ObjectCollection findByUserComment(string $user_comment) Return ChildComment objects filtered by the user_comment column
 * @method     ChildComment[]|ObjectCollection findByUserId(int $user_id) Return ChildComment objects filtered by the user_id column
 * @method     ChildComment[]|ObjectCollection findByThemeId(int $theme_id) Return ChildComment objects filtered by the theme_id column
 * @method     ChildComment[]|ObjectCollection findByThreadId(int $thread_id) Return ChildComment objects filtered by the thread_id column
 * @method     ChildComment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CommentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\CommentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Comment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCommentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCommentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCommentQuery) {
            return $criteria;
        }
        $query = new ChildCommentQuery();
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
     * @return ChildComment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CommentTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CommentTableMap::DATABASE_NAME);
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
     * @return ChildComment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, created_date, updated_date, user_comment, user_id, theme_id, thread_id FROM comment WHERE id = :p0';
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
            /** @var ChildComment $obj */
            $obj = new ChildComment();
            $obj->hydrate($row);
            CommentTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildComment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CommentTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CommentTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByCreatedDate($createdDate = null, $comparison = null)
    {
        if (is_array($createdDate)) {
            $useMinMax = false;
            if (isset($createdDate['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_CREATED_DATE, $createdDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdDate['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_CREATED_DATE, $createdDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_CREATED_DATE, $createdDate, $comparison);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByUpdatedDate($updatedDate = null, $comparison = null)
    {
        if (is_array($updatedDate)) {
            $useMinMax = false;
            if (isset($updatedDate['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_UPDATED_DATE, $updatedDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedDate['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_UPDATED_DATE, $updatedDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_UPDATED_DATE, $updatedDate, $comparison);
    }

    /**
     * Filter the query on the user_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByUserComment('fooValue');   // WHERE user_comment = 'fooValue'
     * $query->filterByUserComment('%fooValue%'); // WHERE user_comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userComment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByUserComment($userComment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userComment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userComment)) {
                $userComment = str_replace('*', '%', $userComment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_USER_COMMENT, $userComment, $comparison);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the theme_id column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeId(1234); // WHERE theme_id = 1234
     * $query->filterByThemeId(array(12, 34)); // WHERE theme_id IN (12, 34)
     * $query->filterByThemeId(array('min' => 12)); // WHERE theme_id > 12
     * </code>
     *
     * @see       filterByThread()
     *
     * @param     mixed $themeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByThemeId($themeId = null, $comparison = null)
    {
        if (is_array($themeId)) {
            $useMinMax = false;
            if (isset($themeId['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_THEME_ID, $themeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($themeId['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_THEME_ID, $themeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_THEME_ID, $themeId, $comparison);
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
     * @see       filterByCommentRelatedByThreadId()
     *
     * @param     mixed $threadId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function filterByThreadId($threadId = null, $comparison = null)
    {
        if (is_array($threadId)) {
            $useMinMax = false;
            if (isset($threadId['min'])) {
                $this->addUsingAlias(CommentTableMap::COL_THREAD_ID, $threadId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($threadId['max'])) {
                $this->addUsingAlias(CommentTableMap::COL_THREAD_ID, $threadId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CommentTableMap::COL_THREAD_ID, $threadId, $comparison);
    }

    /**
     * Filter the query by a related \Comment object
     *
     * @param \Comment|ObjectCollection $comment The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCommentQuery The current query, for fluid interface
     */
    public function filterByCommentRelatedByThreadId($comment, $comparison = null)
    {
        if ($comment instanceof \Comment) {
            return $this
                ->addUsingAlias(CommentTableMap::COL_THREAD_ID, $comment->getId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CommentTableMap::COL_THREAD_ID, $comment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCommentRelatedByThreadId() only accepts arguments of type \Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CommentRelatedByThreadId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function joinCommentRelatedByThreadId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CommentRelatedByThreadId');

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
            $this->addJoinObject($join, 'CommentRelatedByThreadId');
        }

        return $this;
    }

    /**
     * Use the CommentRelatedByThreadId relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentRelatedByThreadIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCommentRelatedByThreadId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CommentRelatedByThreadId', '\CommentQuery');
    }

    /**
     * Filter the query by a related \Thread object
     *
     * @param \Thread|ObjectCollection $thread The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCommentQuery The current query, for fluid interface
     */
    public function filterByThread($thread, $comparison = null)
    {
        if ($thread instanceof \Thread) {
            return $this
                ->addUsingAlias(CommentTableMap::COL_THEME_ID, $thread->getId(), $comparison);
        } elseif ($thread instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CommentTableMap::COL_THEME_ID, $thread->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
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
     * @return ChildCommentQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \User) {
            return $this
                ->addUsingAlias(CommentTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CommentTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildCommentQuery The current query, for fluid interface
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
     * Filter the query by a related \Comment object
     *
     * @param \Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommentQuery The current query, for fluid interface
     */
    public function filterByCommentRelatedById($comment, $comparison = null)
    {
        if ($comment instanceof \Comment) {
            return $this
                ->addUsingAlias(CommentTableMap::COL_ID, $comment->getThreadId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentRelatedByIdQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCommentRelatedById() only accepts arguments of type \Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CommentRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function joinCommentRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CommentRelatedById');

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
            $this->addJoinObject($join, 'CommentRelatedById');
        }

        return $this;
    }

    /**
     * Use the CommentRelatedById relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCommentRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CommentRelatedById', '\CommentQuery');
    }

    /**
     * Filter the query by a related \CommentUserEntry object
     *
     * @param \CommentUserEntry|ObjectCollection $commentUserEntry the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildCommentQuery The current query, for fluid interface
     */
    public function filterByCommentUserEntry($commentUserEntry, $comparison = null)
    {
        if ($commentUserEntry instanceof \CommentUserEntry) {
            return $this
                ->addUsingAlias(CommentTableMap::COL_ID, $commentUserEntry->getCommentId(), $comparison);
        } elseif ($commentUserEntry instanceof ObjectCollection) {
            return $this
                ->useCommentUserEntryQuery()
                ->filterByPrimaryKeys($commentUserEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCommentUserEntry() only accepts arguments of type \CommentUserEntry or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CommentUserEntry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function joinCommentUserEntry($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CommentUserEntry');

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
            $this->addJoinObject($join, 'CommentUserEntry');
        }

        return $this;
    }

    /**
     * Use the CommentUserEntry relation CommentUserEntry object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CommentUserEntryQuery A secondary query class using the current class as primary query
     */
    public function useCommentUserEntryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCommentUserEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CommentUserEntry', '\CommentUserEntryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildComment $comment Object to remove from the list of results
     *
     * @return $this|ChildCommentQuery The current query, for fluid interface
     */
    public function prune($comment = null)
    {
        if ($comment) {
            $this->addUsingAlias(CommentTableMap::COL_ID, $comment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CommentTableMap::clearInstancePool();
            CommentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CommentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            CommentTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            CommentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CommentQuery
