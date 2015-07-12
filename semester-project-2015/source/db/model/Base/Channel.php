<?php

namespace Base;

use \Channel as ChildChannel;
use \ChannelQuery as ChildChannelQuery;
use \ChannelUserEntry as ChildChannelUserEntry;
use \ChannelUserEntryQuery as ChildChannelUserEntryQuery;
use \Thread as ChildThread;
use \ThreadQuery as ChildThreadQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\ChannelTableMap;
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
 * Base class that represents a row from the 'channel' table.
 *
 * 
 *
* @package    propel.generator..Base
*/
abstract class Channel implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\ChannelTableMap';


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
     * The value for the creation_date field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $creation_date;

    /**
     * The value for the updated_date field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        \DateTime
     */
    protected $updated_date;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * @var        ObjectCollection|ChildChannelUserEntry[] Collection to store aggregation of ChildChannelUserEntry objects.
     */
    protected $collChannelUserEntries;
    protected $collChannelUserEntriesPartial;

    /**
     * @var        ObjectCollection|ChildThread[] Collection to store aggregation of ChildThread objects.
     */
    protected $collThreads;
    protected $collThreadsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildChannelUserEntry[]
     */
    protected $channelUserEntriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildThread[]
     */
    protected $threadsScheduledForDeletion = null;

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
     * Initializes internal state of Base\Channel object.
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
     * Compares this with another <code>Channel</code> instance.  If
     * <code>obj</code> is an instance of <code>Channel</code>, delegates to
     * <code>equals(Channel)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Channel The current object, for fluid interface
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
     * Get the [optionally formatted] temporal [creation_date] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreationDate($format = NULL)
    {
        if ($format === null) {
            return $this->creation_date;
        } else {
            return $this->creation_date instanceof \DateTime ? $this->creation_date->format($format) : null;
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
     * Get the [title] column value.
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [description] column value.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ChannelTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [creation_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function setCreationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->creation_date !== null || $dt !== null) {
            if ($this->creation_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->creation_date->format("Y-m-d H:i:s")) {
                $this->creation_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ChannelTableMap::COL_CREATION_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setCreationDate()

    /**
     * Sets the value of [updated_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function setUpdatedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_date !== null || $dt !== null) {
            if ($this->updated_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_date->format("Y-m-d H:i:s")) {
                $this->updated_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ChannelTableMap::COL_UPDATED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedDate()

    /**
     * Set the value of [title] column.
     * 
     * @param string $v new value
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[ChannelTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     * 
     * @param string $v new value
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[ChannelTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ChannelTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ChannelTableMap::translateFieldName('CreationDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->creation_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ChannelTableMap::translateFieldName('UpdatedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ChannelTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ChannelTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = ChannelTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Channel'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ChannelTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildChannelQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collChannelUserEntries = null;

            $this->collThreads = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Channel::setDeleted()
     * @see Channel::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildChannelQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
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
                ChannelTableMap::addInstanceToPool($this);
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

            if ($this->channelUserEntriesScheduledForDeletion !== null) {
                if (!$this->channelUserEntriesScheduledForDeletion->isEmpty()) {
                    \ChannelUserEntryQuery::create()
                        ->filterByPrimaryKeys($this->channelUserEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->channelUserEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collChannelUserEntries !== null) {
                foreach ($this->collChannelUserEntries as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->threadsScheduledForDeletion !== null) {
                if (!$this->threadsScheduledForDeletion->isEmpty()) {
                    \ThreadQuery::create()
                        ->filterByPrimaryKeys($this->threadsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->threadsScheduledForDeletion = null;
                }
            }

            if ($this->collThreads !== null) {
                foreach ($this->collThreads as $referrerFK) {
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

        $this->modifiedColumns[ChannelTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ChannelTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ChannelTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ChannelTableMap::COL_CREATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'creation_date';
        }
        if ($this->isColumnModified(ChannelTableMap::COL_UPDATED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'updated_date';
        }
        if ($this->isColumnModified(ChannelTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(ChannelTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }

        $sql = sprintf(
            'INSERT INTO channel (%s) VALUES (%s)',
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
                    case 'creation_date':                        
                        $stmt->bindValue($identifier, $this->creation_date ? $this->creation_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_date':                        
                        $stmt->bindValue($identifier, $this->updated_date ? $this->updated_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'title':                        
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'description':                        
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
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
        $pos = ChannelTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCreationDate();
                break;
            case 2:
                return $this->getUpdatedDate();
                break;
            case 3:
                return $this->getTitle();
                break;
            case 4:
                return $this->getDescription();
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

        if (isset($alreadyDumpedObjects['Channel'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Channel'][$this->hashCode()] = true;
        $keys = ChannelTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCreationDate(),
            $keys[2] => $this->getUpdatedDate(),
            $keys[3] => $this->getTitle(),
            $keys[4] => $this->getDescription(),
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
            if (null !== $this->collChannelUserEntries) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'channelUserEntries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'channel_user_entries';
                        break;
                    default:
                        $key = 'ChannelUserEntries';
                }
        
                $result[$key] = $this->collChannelUserEntries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collThreads) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'threads';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'threads';
                        break;
                    default:
                        $key = 'Threads';
                }
        
                $result[$key] = $this->collThreads->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Channel
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ChannelTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Channel
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCreationDate($value);
                break;
            case 2:
                $this->setUpdatedDate($value);
                break;
            case 3:
                $this->setTitle($value);
                break;
            case 4:
                $this->setDescription($value);
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
        $keys = ChannelTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setCreationDate($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUpdatedDate($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTitle($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setDescription($arr[$keys[4]]);
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
     * @return $this|\Channel The current object, for fluid interface
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
        $criteria = new Criteria(ChannelTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ChannelTableMap::COL_ID)) {
            $criteria->add(ChannelTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ChannelTableMap::COL_CREATION_DATE)) {
            $criteria->add(ChannelTableMap::COL_CREATION_DATE, $this->creation_date);
        }
        if ($this->isColumnModified(ChannelTableMap::COL_UPDATED_DATE)) {
            $criteria->add(ChannelTableMap::COL_UPDATED_DATE, $this->updated_date);
        }
        if ($this->isColumnModified(ChannelTableMap::COL_TITLE)) {
            $criteria->add(ChannelTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(ChannelTableMap::COL_DESCRIPTION)) {
            $criteria->add(ChannelTableMap::COL_DESCRIPTION, $this->description);
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
        $criteria = ChildChannelQuery::create();
        $criteria->add(ChannelTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Channel (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCreationDate($this->getCreationDate());
        $copyObj->setUpdatedDate($this->getUpdatedDate());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChannelUserEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChannelUserEntry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getThreads() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addThread($relObj->copy($deepCopy));
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
     * @return \Channel Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ChannelUserEntry' == $relationName) {
            return $this->initChannelUserEntries();
        }
        if ('Thread' == $relationName) {
            return $this->initThreads();
        }
    }

    /**
     * Clears out the collChannelUserEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChannelUserEntries()
     */
    public function clearChannelUserEntries()
    {
        $this->collChannelUserEntries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChannelUserEntries collection loaded partially.
     */
    public function resetPartialChannelUserEntries($v = true)
    {
        $this->collChannelUserEntriesPartial = $v;
    }

    /**
     * Initializes the collChannelUserEntries collection.
     *
     * By default this just sets the collChannelUserEntries collection to an empty array (like clearcollChannelUserEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChannelUserEntries($overrideExisting = true)
    {
        if (null !== $this->collChannelUserEntries && !$overrideExisting) {
            return;
        }
        $this->collChannelUserEntries = new ObjectCollection();
        $this->collChannelUserEntries->setModel('\ChannelUserEntry');
    }

    /**
     * Gets an array of ChildChannelUserEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChannel is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildChannelUserEntry[] List of ChildChannelUserEntry objects
     * @throws PropelException
     */
    public function getChannelUserEntries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChannelUserEntriesPartial && !$this->isNew();
        if (null === $this->collChannelUserEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChannelUserEntries) {
                // return empty collection
                $this->initChannelUserEntries();
            } else {
                $collChannelUserEntries = ChildChannelUserEntryQuery::create(null, $criteria)
                    ->filterByChannel($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChannelUserEntriesPartial && count($collChannelUserEntries)) {
                        $this->initChannelUserEntries(false);

                        foreach ($collChannelUserEntries as $obj) {
                            if (false == $this->collChannelUserEntries->contains($obj)) {
                                $this->collChannelUserEntries->append($obj);
                            }
                        }

                        $this->collChannelUserEntriesPartial = true;
                    }

                    return $collChannelUserEntries;
                }

                if ($partial && $this->collChannelUserEntries) {
                    foreach ($this->collChannelUserEntries as $obj) {
                        if ($obj->isNew()) {
                            $collChannelUserEntries[] = $obj;
                        }
                    }
                }

                $this->collChannelUserEntries = $collChannelUserEntries;
                $this->collChannelUserEntriesPartial = false;
            }
        }

        return $this->collChannelUserEntries;
    }

    /**
     * Sets a collection of ChildChannelUserEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $channelUserEntries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildChannel The current object (for fluent API support)
     */
    public function setChannelUserEntries(Collection $channelUserEntries, ConnectionInterface $con = null)
    {
        /** @var ChildChannelUserEntry[] $channelUserEntriesToDelete */
        $channelUserEntriesToDelete = $this->getChannelUserEntries(new Criteria(), $con)->diff($channelUserEntries);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->channelUserEntriesScheduledForDeletion = clone $channelUserEntriesToDelete;

        foreach ($channelUserEntriesToDelete as $channelUserEntryRemoved) {
            $channelUserEntryRemoved->setChannel(null);
        }

        $this->collChannelUserEntries = null;
        foreach ($channelUserEntries as $channelUserEntry) {
            $this->addChannelUserEntry($channelUserEntry);
        }

        $this->collChannelUserEntries = $channelUserEntries;
        $this->collChannelUserEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChannelUserEntry objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChannelUserEntry objects.
     * @throws PropelException
     */
    public function countChannelUserEntries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChannelUserEntriesPartial && !$this->isNew();
        if (null === $this->collChannelUserEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChannelUserEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChannelUserEntries());
            }

            $query = ChildChannelUserEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChannel($this)
                ->count($con);
        }

        return count($this->collChannelUserEntries);
    }

    /**
     * Method called to associate a ChildChannelUserEntry object to this object
     * through the ChildChannelUserEntry foreign key attribute.
     *
     * @param  ChildChannelUserEntry $l ChildChannelUserEntry
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function addChannelUserEntry(ChildChannelUserEntry $l)
    {
        if ($this->collChannelUserEntries === null) {
            $this->initChannelUserEntries();
            $this->collChannelUserEntriesPartial = true;
        }

        if (!$this->collChannelUserEntries->contains($l)) {
            $this->doAddChannelUserEntry($l);
        }

        return $this;
    }

    /**
     * @param ChildChannelUserEntry $channelUserEntry The ChildChannelUserEntry object to add.
     */
    protected function doAddChannelUserEntry(ChildChannelUserEntry $channelUserEntry)
    {
        $this->collChannelUserEntries[]= $channelUserEntry;
        $channelUserEntry->setChannel($this);
    }

    /**
     * @param  ChildChannelUserEntry $channelUserEntry The ChildChannelUserEntry object to remove.
     * @return $this|ChildChannel The current object (for fluent API support)
     */
    public function removeChannelUserEntry(ChildChannelUserEntry $channelUserEntry)
    {
        if ($this->getChannelUserEntries()->contains($channelUserEntry)) {
            $pos = $this->collChannelUserEntries->search($channelUserEntry);
            $this->collChannelUserEntries->remove($pos);
            if (null === $this->channelUserEntriesScheduledForDeletion) {
                $this->channelUserEntriesScheduledForDeletion = clone $this->collChannelUserEntries;
                $this->channelUserEntriesScheduledForDeletion->clear();
            }
            $this->channelUserEntriesScheduledForDeletion[]= clone $channelUserEntry;
            $channelUserEntry->setChannel(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Channel is new, it will return
     * an empty collection; or if this Channel has previously
     * been saved, it will retrieve related ChannelUserEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Channel.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildChannelUserEntry[] List of ChildChannelUserEntry objects
     */
    public function getChannelUserEntriesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChannelUserEntryQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getChannelUserEntries($query, $con);
    }

    /**
     * Clears out the collThreads collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addThreads()
     */
    public function clearThreads()
    {
        $this->collThreads = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collThreads collection loaded partially.
     */
    public function resetPartialThreads($v = true)
    {
        $this->collThreadsPartial = $v;
    }

    /**
     * Initializes the collThreads collection.
     *
     * By default this just sets the collThreads collection to an empty array (like clearcollThreads());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initThreads($overrideExisting = true)
    {
        if (null !== $this->collThreads && !$overrideExisting) {
            return;
        }
        $this->collThreads = new ObjectCollection();
        $this->collThreads->setModel('\Thread');
    }

    /**
     * Gets an array of ChildThread objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChannel is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildThread[] List of ChildThread objects
     * @throws PropelException
     */
    public function getThreads(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadsPartial && !$this->isNew();
        if (null === $this->collThreads || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collThreads) {
                // return empty collection
                $this->initThreads();
            } else {
                $collThreads = ChildThreadQuery::create(null, $criteria)
                    ->filterByChannel($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collThreadsPartial && count($collThreads)) {
                        $this->initThreads(false);

                        foreach ($collThreads as $obj) {
                            if (false == $this->collThreads->contains($obj)) {
                                $this->collThreads->append($obj);
                            }
                        }

                        $this->collThreadsPartial = true;
                    }

                    return $collThreads;
                }

                if ($partial && $this->collThreads) {
                    foreach ($this->collThreads as $obj) {
                        if ($obj->isNew()) {
                            $collThreads[] = $obj;
                        }
                    }
                }

                $this->collThreads = $collThreads;
                $this->collThreadsPartial = false;
            }
        }

        return $this->collThreads;
    }

    /**
     * Sets a collection of ChildThread objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $threads A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildChannel The current object (for fluent API support)
     */
    public function setThreads(Collection $threads, ConnectionInterface $con = null)
    {
        /** @var ChildThread[] $threadsToDelete */
        $threadsToDelete = $this->getThreads(new Criteria(), $con)->diff($threads);

        
        $this->threadsScheduledForDeletion = $threadsToDelete;

        foreach ($threadsToDelete as $threadRemoved) {
            $threadRemoved->setChannel(null);
        }

        $this->collThreads = null;
        foreach ($threads as $thread) {
            $this->addThread($thread);
        }

        $this->collThreads = $threads;
        $this->collThreadsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Thread objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Thread objects.
     * @throws PropelException
     */
    public function countThreads(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadsPartial && !$this->isNew();
        if (null === $this->collThreads || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collThreads) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getThreads());
            }

            $query = ChildThreadQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChannel($this)
                ->count($con);
        }

        return count($this->collThreads);
    }

    /**
     * Method called to associate a ChildThread object to this object
     * through the ChildThread foreign key attribute.
     *
     * @param  ChildThread $l ChildThread
     * @return $this|\Channel The current object (for fluent API support)
     */
    public function addThread(ChildThread $l)
    {
        if ($this->collThreads === null) {
            $this->initThreads();
            $this->collThreadsPartial = true;
        }

        if (!$this->collThreads->contains($l)) {
            $this->doAddThread($l);
        }

        return $this;
    }

    /**
     * @param ChildThread $thread The ChildThread object to add.
     */
    protected function doAddThread(ChildThread $thread)
    {
        $this->collThreads[]= $thread;
        $thread->setChannel($this);
    }

    /**
     * @param  ChildThread $thread The ChildThread object to remove.
     * @return $this|ChildChannel The current object (for fluent API support)
     */
    public function removeThread(ChildThread $thread)
    {
        if ($this->getThreads()->contains($thread)) {
            $pos = $this->collThreads->search($thread);
            $this->collThreads->remove($pos);
            if (null === $this->threadsScheduledForDeletion) {
                $this->threadsScheduledForDeletion = clone $this->collThreads;
                $this->threadsScheduledForDeletion->clear();
            }
            $this->threadsScheduledForDeletion[]= clone $thread;
            $thread->setChannel(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Channel is new, it will return
     * an empty collection; or if this Channel has previously
     * been saved, it will retrieve related Threads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Channel.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildThread[] List of ChildThread objects
     */
    public function getThreadsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildThreadQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getThreads($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->creation_date = null;
        $this->updated_date = null;
        $this->title = null;
        $this->description = null;
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
            if ($this->collChannelUserEntries) {
                foreach ($this->collChannelUserEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collThreads) {
                foreach ($this->collThreads as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collChannelUserEntries = null;
        $this->collThreads = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ChannelTableMap::DEFAULT_STRING_FORMAT);
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
