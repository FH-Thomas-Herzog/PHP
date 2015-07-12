<?php

namespace Base;

use \Comment as ChildComment;
use \CommentQuery as ChildCommentQuery;
use \CommentUserEntry as ChildCommentUserEntry;
use \CommentUserEntryQuery as ChildCommentUserEntryQuery;
use \Thread as ChildThread;
use \ThreadQuery as ChildThreadQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\CommentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'comment' table.
 *
 * 
 *
* @package    propel.generator..Base
*/
abstract class Comment implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\CommentTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the created_date field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $created_date;

    /**
     * The value for the updated_date field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $updated_date;

    /**
     * The value for the user_comment field.
     * @var        string
     */
    protected $user_comment;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the theme_id field.
     * @var        int
     */
    protected $theme_id;

    /**
     * The value for the thread_id field.
     * @var        int
     */
    protected $thread_id;

    /**
     * @var        ChildComment
     */
    protected $aCommentRelatedByThreadId;

    /**
     * @var        ChildThread
     */
    protected $aThread;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildComment[] Collection to store aggregation of ChildComment objects.
     */
    protected $collCommentsRelatedById;
    protected $collCommentsRelatedByIdPartial;

    /**
     * @var        ObjectCollection|ChildCommentUserEntry[] Collection to store aggregation of ChildCommentUserEntry objects.
     */
    protected $collCommentUserEntries;
    protected $collCommentUserEntriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildComment[]
     */
    protected $commentsRelatedByIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCommentUserEntry[]
     */
    protected $commentUserEntriesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of Base\Comment object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Comment</code> instance.  If
     * <code>obj</code> is an instance of <code>Comment</code>, delegates to
     * <code>equals(Comment)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Comment The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [optionally formatted] temporal [created_date] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedDate($format = NULL)
    {
        if ($format === null) {
            return $this->created_date;
        } else {
            return $this->created_date instanceof \DateTime ? $this->created_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_date] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedDate($format = NULL)
    {
        if ($format === null) {
            return $this->updated_date;
        } else {
            return $this->updated_date instanceof \DateTime ? $this->updated_date->format($format) : null;
        }
    }

    /**
     * Get the [user_comment] column value.
     * 
     * @return string
     */
    public function getUserComment()
    {
        return $this->user_comment;
    }

    /**
     * Get the [user_id] column value.
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [theme_id] column value.
     * 
     * @return int
     */
    public function getThemeId()
    {
        return $this->theme_id;
    }

    /**
     * Get the [thread_id] column value.
     * 
     * @return int
     */
    public function getThreadId()
    {
        return $this->thread_id;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CommentTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [created_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setCreatedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_date !== null || $dt !== null) {
            if ($this->created_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_date->format("Y-m-d H:i:s")) {
                $this->created_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CommentTableMap::COL_CREATED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedDate()

    /**
     * Sets the value of [updated_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setUpdatedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_date !== null || $dt !== null) {
            if ($this->updated_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_date->format("Y-m-d H:i:s")) {
                $this->updated_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CommentTableMap::COL_UPDATED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedDate()

    /**
     * Set the value of [user_comment] column.
     * 
     * @param string $v new value
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setUserComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_comment !== $v) {
            $this->user_comment = $v;
            $this->modifiedColumns[CommentTableMap::COL_USER_COMMENT] = true;
        }

        return $this;
    } // setUserComment()

    /**
     * Set the value of [user_id] column.
     * 
     * @param int $v new value
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[CommentTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [theme_id] column.
     * 
     * @param int $v new value
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setThemeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->theme_id !== $v) {
            $this->theme_id = $v;
            $this->modifiedColumns[CommentTableMap::COL_THEME_ID] = true;
        }

        if ($this->aThread !== null && $this->aThread->getId() !== $v) {
            $this->aThread = null;
        }

        return $this;
    } // setThemeId()

    /**
     * Set the value of [thread_id] column.
     * 
     * @param int $v new value
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function setThreadId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->thread_id !== $v) {
            $this->thread_id = $v;
            $this->modifiedColumns[CommentTableMap::COL_THREAD_ID] = true;
        }

        if ($this->aCommentRelatedByThreadId !== null && $this->aCommentRelatedByThreadId->getId() !== $v) {
            $this->aCommentRelatedByThreadId = null;
        }

        return $this;
    } // setThreadId()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CommentTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CommentTableMap::translateFieldName('CreatedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CommentTableMap::translateFieldName('UpdatedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CommentTableMap::translateFieldName('UserComment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_comment = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CommentTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CommentTableMap::translateFieldName('ThemeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->theme_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CommentTableMap::translateFieldName('ThreadId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->thread_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = CommentTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Comment'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aThread !== null && $this->theme_id !== $this->aThread->getId()) {
            $this->aThread = null;
        }
        if ($this->aCommentRelatedByThreadId !== null && $this->thread_id !== $this->aCommentRelatedByThreadId->getId()) {
            $this->aCommentRelatedByThreadId = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CommentTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCommentQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCommentRelatedByThreadId = null;
            $this->aThread = null;
            $this->aUser = null;
            $this->collCommentsRelatedById = null;

            $this->collCommentUserEntries = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Comment::setDeleted()
     * @see Comment::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCommentQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CommentTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCommentRelatedByThreadId !== null) {
                if ($this->aCommentRelatedByThreadId->isModified() || $this->aCommentRelatedByThreadId->isNew()) {
                    $affectedRows += $this->aCommentRelatedByThreadId->save($con);
                }
                $this->setCommentRelatedByThreadId($this->aCommentRelatedByThreadId);
            }

            if ($this->aThread !== null) {
                if ($this->aThread->isModified() || $this->aThread->isNew()) {
                    $affectedRows += $this->aThread->save($con);
                }
                $this->setThread($this->aThread);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->commentsRelatedByIdScheduledForDeletion !== null) {
                if (!$this->commentsRelatedByIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->commentsRelatedByIdScheduledForDeletion as $commentRelatedById) {
                        // need to save related object because we set the relation to null
                        $commentRelatedById->save($con);
                    }
                    $this->commentsRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collCommentsRelatedById !== null) {
                foreach ($this->collCommentsRelatedById as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->commentUserEntriesScheduledForDeletion !== null) {
                if (!$this->commentUserEntriesScheduledForDeletion->isEmpty()) {
                    \CommentUserEntryQuery::create()
                        ->filterByPrimaryKeys($this->commentUserEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->commentUserEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collCommentUserEntries !== null) {
                foreach ($this->collCommentUserEntries as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[CommentTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CommentTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CommentTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(CommentTableMap::COL_CREATED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'created_date';
        }
        if ($this->isColumnModified(CommentTableMap::COL_UPDATED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'updated_date';
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER_COMMENT)) {
            $modifiedColumns[':p' . $index++]  = 'user_comment';
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(CommentTableMap::COL_THEME_ID)) {
            $modifiedColumns[':p' . $index++]  = 'theme_id';
        }
        if ($this->isColumnModified(CommentTableMap::COL_THREAD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'thread_id';
        }

        $sql = sprintf(
            'INSERT INTO comment (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':                        
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'created_date':                        
                        $stmt->bindValue($identifier, $this->created_date ? $this->created_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_date':                        
                        $stmt->bindValue($identifier, $this->updated_date ? $this->updated_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'user_comment':                        
                        $stmt->bindValue($identifier, $this->user_comment, PDO::PARAM_STR);
                        break;
                    case 'user_id':                        
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'theme_id':                        
                        $stmt->bindValue($identifier, $this->theme_id, PDO::PARAM_INT);
                        break;
                    case 'thread_id':                        
                        $stmt->bindValue($identifier, $this->thread_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CommentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCreatedDate();
                break;
            case 2:
                return $this->getUpdatedDate();
                break;
            case 3:
                return $this->getUserComment();
                break;
            case 4:
                return $this->getUserId();
                break;
            case 5:
                return $this->getThemeId();
                break;
            case 6:
                return $this->getThreadId();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Comment'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Comment'][$this->hashCode()] = true;
        $keys = CommentTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCreatedDate(),
            $keys[2] => $this->getUpdatedDate(),
            $keys[3] => $this->getUserComment(),
            $keys[4] => $this->getUserId(),
            $keys[5] => $this->getThemeId(),
            $keys[6] => $this->getThreadId(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[1]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[1]];
            $result[$keys[1]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }
        
        if ($result[$keys[2]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[2]];
            $result[$keys[2]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aCommentRelatedByThreadId) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'comment';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comment';
                        break;
                    default:
                        $key = 'Comment';
                }
        
                $result[$key] = $this->aCommentRelatedByThreadId->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aThread) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'thread';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'thread';
                        break;
                    default:
                        $key = 'Thread';
                }
        
                $result[$key] = $this->aThread->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }
        
                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCommentsRelatedById) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'comments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comments';
                        break;
                    default:
                        $key = 'Comments';
                }
        
                $result[$key] = $this->collCommentsRelatedById->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCommentUserEntries) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'commentUserEntries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comment_user_entries';
                        break;
                    default:
                        $key = 'CommentUserEntries';
                }
        
                $result[$key] = $this->collCommentUserEntries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Comment
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CommentTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Comment
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCreatedDate($value);
                break;
            case 2:
                $this->setUpdatedDate($value);
                break;
            case 3:
                $this->setUserComment($value);
                break;
            case 4:
                $this->setUserId($value);
                break;
            case 5:
                $this->setThemeId($value);
                break;
            case 6:
                $this->setThreadId($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = CommentTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setCreatedDate($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUpdatedDate($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserComment($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUserId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setThemeId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setThreadId($arr[$keys[6]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Comment The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CommentTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CommentTableMap::COL_ID)) {
            $criteria->add(CommentTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CommentTableMap::COL_CREATED_DATE)) {
            $criteria->add(CommentTableMap::COL_CREATED_DATE, $this->created_date);
        }
        if ($this->isColumnModified(CommentTableMap::COL_UPDATED_DATE)) {
            $criteria->add(CommentTableMap::COL_UPDATED_DATE, $this->updated_date);
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER_COMMENT)) {
            $criteria->add(CommentTableMap::COL_USER_COMMENT, $this->user_comment);
        }
        if ($this->isColumnModified(CommentTableMap::COL_USER_ID)) {
            $criteria->add(CommentTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(CommentTableMap::COL_THEME_ID)) {
            $criteria->add(CommentTableMap::COL_THEME_ID, $this->theme_id);
        }
        if ($this->isColumnModified(CommentTableMap::COL_THREAD_ID)) {
            $criteria->add(CommentTableMap::COL_THREAD_ID, $this->thread_id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildCommentQuery::create();
        $criteria->add(CommentTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }
        
    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Comment (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCreatedDate($this->getCreatedDate());
        $copyObj->setUpdatedDate($this->getUpdatedDate());
        $copyObj->setUserComment($this->getUserComment());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setThemeId($this->getThemeId());
        $copyObj->setThreadId($this->getThreadId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getCommentsRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCommentRelatedById($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCommentUserEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCommentUserEntry($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Comment Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildComment object.
     *
     * @param  ChildComment $v
     * @return $this|\Comment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCommentRelatedByThreadId(ChildComment $v = null)
    {
        if ($v === null) {
            $this->setThreadId(NULL);
        } else {
            $this->setThreadId($v->getId());
        }

        $this->aCommentRelatedByThreadId = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildComment object, it will not be re-added.
        if ($v !== null) {
            $v->addCommentRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildComment object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildComment The associated ChildComment object.
     * @throws PropelException
     */
    public function getCommentRelatedByThreadId(ConnectionInterface $con = null)
    {
        if ($this->aCommentRelatedByThreadId === null && ($this->thread_id !== null)) {
            $this->aCommentRelatedByThreadId = ChildCommentQuery::create()->findPk($this->thread_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCommentRelatedByThreadId->addCommentsRelatedById($this);
             */
        }

        return $this->aCommentRelatedByThreadId;
    }

    /**
     * Declares an association between this object and a ChildThread object.
     *
     * @param  ChildThread $v
     * @return $this|\Comment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setThread(ChildThread $v = null)
    {
        if ($v === null) {
            $this->setThemeId(NULL);
        } else {
            $this->setThemeId($v->getId());
        }

        $this->aThread = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildThread object, it will not be re-added.
        if ($v !== null) {
            $v->addComment($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildThread object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildThread The associated ChildThread object.
     * @throws PropelException
     */
    public function getThread(ConnectionInterface $con = null)
    {
        if ($this->aThread === null && ($this->theme_id !== null)) {
            $this->aThread = ChildThreadQuery::create()->findPk($this->theme_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aThread->addComments($this);
             */
        }

        return $this->aThread;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Comment The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addComment($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addComments($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CommentRelatedById' == $relationName) {
            return $this->initCommentsRelatedById();
        }
        if ('CommentUserEntry' == $relationName) {
            return $this->initCommentUserEntries();
        }
    }

    /**
     * Clears out the collCommentsRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCommentsRelatedById()
     */
    public function clearCommentsRelatedById()
    {
        $this->collCommentsRelatedById = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCommentsRelatedById collection loaded partially.
     */
    public function resetPartialCommentsRelatedById($v = true)
    {
        $this->collCommentsRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collCommentsRelatedById collection.
     *
     * By default this just sets the collCommentsRelatedById collection to an empty array (like clearcollCommentsRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCommentsRelatedById($overrideExisting = true)
    {
        if (null !== $this->collCommentsRelatedById && !$overrideExisting) {
            return;
        }
        $this->collCommentsRelatedById = new ObjectCollection();
        $this->collCommentsRelatedById->setModel('\Comment');
    }

    /**
     * Gets an array of ChildComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildComment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     * @throws PropelException
     */
    public function getCommentsRelatedById(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCommentsRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCommentsRelatedById) {
                // return empty collection
                $this->initCommentsRelatedById();
            } else {
                $collCommentsRelatedById = ChildCommentQuery::create(null, $criteria)
                    ->filterByCommentRelatedByThreadId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentsRelatedByIdPartial && count($collCommentsRelatedById)) {
                        $this->initCommentsRelatedById(false);

                        foreach ($collCommentsRelatedById as $obj) {
                            if (false == $this->collCommentsRelatedById->contains($obj)) {
                                $this->collCommentsRelatedById->append($obj);
                            }
                        }

                        $this->collCommentsRelatedByIdPartial = true;
                    }

                    return $collCommentsRelatedById;
                }

                if ($partial && $this->collCommentsRelatedById) {
                    foreach ($this->collCommentsRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collCommentsRelatedById[] = $obj;
                        }
                    }
                }

                $this->collCommentsRelatedById = $collCommentsRelatedById;
                $this->collCommentsRelatedByIdPartial = false;
            }
        }

        return $this->collCommentsRelatedById;
    }

    /**
     * Sets a collection of ChildComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $commentsRelatedById A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildComment The current object (for fluent API support)
     */
    public function setCommentsRelatedById(Collection $commentsRelatedById, ConnectionInterface $con = null)
    {
        /** @var ChildComment[] $commentsRelatedByIdToDelete */
        $commentsRelatedByIdToDelete = $this->getCommentsRelatedById(new Criteria(), $con)->diff($commentsRelatedById);

        
        $this->commentsRelatedByIdScheduledForDeletion = $commentsRelatedByIdToDelete;

        foreach ($commentsRelatedByIdToDelete as $commentRelatedByIdRemoved) {
            $commentRelatedByIdRemoved->setCommentRelatedByThreadId(null);
        }

        $this->collCommentsRelatedById = null;
        foreach ($commentsRelatedById as $commentRelatedById) {
            $this->addCommentRelatedById($commentRelatedById);
        }

        $this->collCommentsRelatedById = $commentsRelatedById;
        $this->collCommentsRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Comment objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Comment objects.
     * @throws PropelException
     */
    public function countCommentsRelatedById(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCommentsRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCommentsRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCommentsRelatedById());
            }

            $query = ChildCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCommentRelatedByThreadId($this)
                ->count($con);
        }

        return count($this->collCommentsRelatedById);
    }

    /**
     * Method called to associate a ChildComment object to this object
     * through the ChildComment foreign key attribute.
     *
     * @param  ChildComment $l ChildComment
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function addCommentRelatedById(ChildComment $l)
    {
        if ($this->collCommentsRelatedById === null) {
            $this->initCommentsRelatedById();
            $this->collCommentsRelatedByIdPartial = true;
        }

        if (!$this->collCommentsRelatedById->contains($l)) {
            $this->doAddCommentRelatedById($l);
        }

        return $this;
    }

    /**
     * @param ChildComment $commentRelatedById The ChildComment object to add.
     */
    protected function doAddCommentRelatedById(ChildComment $commentRelatedById)
    {
        $this->collCommentsRelatedById[]= $commentRelatedById;
        $commentRelatedById->setCommentRelatedByThreadId($this);
    }

    /**
     * @param  ChildComment $commentRelatedById The ChildComment object to remove.
     * @return $this|ChildComment The current object (for fluent API support)
     */
    public function removeCommentRelatedById(ChildComment $commentRelatedById)
    {
        if ($this->getCommentsRelatedById()->contains($commentRelatedById)) {
            $pos = $this->collCommentsRelatedById->search($commentRelatedById);
            $this->collCommentsRelatedById->remove($pos);
            if (null === $this->commentsRelatedByIdScheduledForDeletion) {
                $this->commentsRelatedByIdScheduledForDeletion = clone $this->collCommentsRelatedById;
                $this->commentsRelatedByIdScheduledForDeletion->clear();
            }
            $this->commentsRelatedByIdScheduledForDeletion[]= $commentRelatedById;
            $commentRelatedById->setCommentRelatedByThreadId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Comment is new, it will return
     * an empty collection; or if this Comment has previously
     * been saved, it will retrieve related CommentsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Comment.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsRelatedByIdJoinThread(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('Thread', $joinBehavior);

        return $this->getCommentsRelatedById($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Comment is new, it will return
     * an empty collection; or if this Comment has previously
     * been saved, it will retrieve related CommentsRelatedById from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Comment.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsRelatedByIdJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getCommentsRelatedById($query, $con);
    }

    /**
     * Clears out the collCommentUserEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCommentUserEntries()
     */
    public function clearCommentUserEntries()
    {
        $this->collCommentUserEntries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCommentUserEntries collection loaded partially.
     */
    public function resetPartialCommentUserEntries($v = true)
    {
        $this->collCommentUserEntriesPartial = $v;
    }

    /**
     * Initializes the collCommentUserEntries collection.
     *
     * By default this just sets the collCommentUserEntries collection to an empty array (like clearcollCommentUserEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCommentUserEntries($overrideExisting = true)
    {
        if (null !== $this->collCommentUserEntries && !$overrideExisting) {
            return;
        }
        $this->collCommentUserEntries = new ObjectCollection();
        $this->collCommentUserEntries->setModel('\CommentUserEntry');
    }

    /**
     * Gets an array of ChildCommentUserEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildComment is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCommentUserEntry[] List of ChildCommentUserEntry objects
     * @throws PropelException
     */
    public function getCommentUserEntries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentUserEntriesPartial && !$this->isNew();
        if (null === $this->collCommentUserEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCommentUserEntries) {
                // return empty collection
                $this->initCommentUserEntries();
            } else {
                $collCommentUserEntries = ChildCommentUserEntryQuery::create(null, $criteria)
                    ->filterByComment($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentUserEntriesPartial && count($collCommentUserEntries)) {
                        $this->initCommentUserEntries(false);

                        foreach ($collCommentUserEntries as $obj) {
                            if (false == $this->collCommentUserEntries->contains($obj)) {
                                $this->collCommentUserEntries->append($obj);
                            }
                        }

                        $this->collCommentUserEntriesPartial = true;
                    }

                    return $collCommentUserEntries;
                }

                if ($partial && $this->collCommentUserEntries) {
                    foreach ($this->collCommentUserEntries as $obj) {
                        if ($obj->isNew()) {
                            $collCommentUserEntries[] = $obj;
                        }
                    }
                }

                $this->collCommentUserEntries = $collCommentUserEntries;
                $this->collCommentUserEntriesPartial = false;
            }
        }

        return $this->collCommentUserEntries;
    }

    /**
     * Sets a collection of ChildCommentUserEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $commentUserEntries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildComment The current object (for fluent API support)
     */
    public function setCommentUserEntries(Collection $commentUserEntries, ConnectionInterface $con = null)
    {
        /** @var ChildCommentUserEntry[] $commentUserEntriesToDelete */
        $commentUserEntriesToDelete = $this->getCommentUserEntries(new Criteria(), $con)->diff($commentUserEntries);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->commentUserEntriesScheduledForDeletion = clone $commentUserEntriesToDelete;

        foreach ($commentUserEntriesToDelete as $commentUserEntryRemoved) {
            $commentUserEntryRemoved->setComment(null);
        }

        $this->collCommentUserEntries = null;
        foreach ($commentUserEntries as $commentUserEntry) {
            $this->addCommentUserEntry($commentUserEntry);
        }

        $this->collCommentUserEntries = $commentUserEntries;
        $this->collCommentUserEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CommentUserEntry objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CommentUserEntry objects.
     * @throws PropelException
     */
    public function countCommentUserEntries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentUserEntriesPartial && !$this->isNew();
        if (null === $this->collCommentUserEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCommentUserEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCommentUserEntries());
            }

            $query = ChildCommentUserEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByComment($this)
                ->count($con);
        }

        return count($this->collCommentUserEntries);
    }

    /**
     * Method called to associate a ChildCommentUserEntry object to this object
     * through the ChildCommentUserEntry foreign key attribute.
     *
     * @param  ChildCommentUserEntry $l ChildCommentUserEntry
     * @return $this|\Comment The current object (for fluent API support)
     */
    public function addCommentUserEntry(ChildCommentUserEntry $l)
    {
        if ($this->collCommentUserEntries === null) {
            $this->initCommentUserEntries();
            $this->collCommentUserEntriesPartial = true;
        }

        if (!$this->collCommentUserEntries->contains($l)) {
            $this->doAddCommentUserEntry($l);
        }

        return $this;
    }

    /**
     * @param ChildCommentUserEntry $commentUserEntry The ChildCommentUserEntry object to add.
     */
    protected function doAddCommentUserEntry(ChildCommentUserEntry $commentUserEntry)
    {
        $this->collCommentUserEntries[]= $commentUserEntry;
        $commentUserEntry->setComment($this);
    }

    /**
     * @param  ChildCommentUserEntry $commentUserEntry The ChildCommentUserEntry object to remove.
     * @return $this|ChildComment The current object (for fluent API support)
     */
    public function removeCommentUserEntry(ChildCommentUserEntry $commentUserEntry)
    {
        if ($this->getCommentUserEntries()->contains($commentUserEntry)) {
            $pos = $this->collCommentUserEntries->search($commentUserEntry);
            $this->collCommentUserEntries->remove($pos);
            if (null === $this->commentUserEntriesScheduledForDeletion) {
                $this->commentUserEntriesScheduledForDeletion = clone $this->collCommentUserEntries;
                $this->commentUserEntriesScheduledForDeletion->clear();
            }
            $this->commentUserEntriesScheduledForDeletion[]= clone $commentUserEntry;
            $commentUserEntry->setComment(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Comment is new, it will return
     * an empty collection; or if this Comment has previously
     * been saved, it will retrieve related CommentUserEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Comment.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCommentUserEntry[] List of ChildCommentUserEntry objects
     */
    public function getCommentUserEntriesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentUserEntryQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getCommentUserEntries($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCommentRelatedByThreadId) {
            $this->aCommentRelatedByThreadId->removeCommentRelatedById($this);
        }
        if (null !== $this->aThread) {
            $this->aThread->removeComment($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeComment($this);
        }
        $this->id = null;
        $this->created_date = null;
        $this->updated_date = null;
        $this->user_comment = null;
        $this->user_id = null;
        $this->theme_id = null;
        $this->thread_id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collCommentsRelatedById) {
                foreach ($this->collCommentsRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCommentUserEntries) {
                foreach ($this->collCommentUserEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collCommentsRelatedById = null;
        $this->collCommentUserEntries = null;
        $this->aCommentRelatedByThreadId = null;
        $this->aThread = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CommentTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
