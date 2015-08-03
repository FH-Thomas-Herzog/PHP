<?php

namespace Base;

use \ChannelUserEntry as ChildChannelUserEntry;
use \ChannelUserEntryQuery as ChildChannelUserEntryQuery;
use \Comment as ChildComment;
use \CommentQuery as ChildCommentQuery;
use \CommentUserEntry as ChildCommentUserEntry;
use \CommentUserEntryQuery as ChildCommentUserEntryQuery;
use \Locale as ChildLocale;
use \LocaleQuery as ChildLocaleQuery;
use \Thread as ChildThread;
use \ThreadQuery as ChildThreadQuery;
use \ThreadUserEntry as ChildThreadUserEntry;
use \ThreadUserEntryQuery as ChildThreadUserEntryQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \UserType as ChildUserType;
use \UserTypeQuery as ChildUserTypeQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\UserTableMap;
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
 * Base class that represents a row from the 'user' table.
 *
 * 
 *
* @package    propel.generator..Base
*/
abstract class User implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the deleted_date field.
     * @var        \DateTime
     */
    protected $deleted_date;

    /**
     * The value for the blocked_date field.
     * @var        \DateTime
     */
    protected $blocked_date;

    /**
     * The value for the firstname field.
     * @var        string
     */
    protected $firstname;

    /**
     * The value for the lastname field.
     * @var        string
     */
    protected $lastname;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the deleted_flag field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $deleted_flag;

    /**
     * The value for the blocked_flag field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $blocked_flag;

    /**
     * The value for the locale_id field.
     * @var        string
     */
    protected $locale_id;

    /**
     * The value for the user_type_id field.
     * @var        string
     */
    protected $user_type_id;

    /**
     * @var        ChildLocale
     */
    protected $aLocale;

    /**
     * @var        ChildUserType
     */
    protected $aUserType;

    /**
     * @var        ObjectCollection|ChildChannelUserEntry[] Collection to store aggregation of ChildChannelUserEntry objects.
     */
    protected $collChannelUserEntries;
    protected $collChannelUserEntriesPartial;

    /**
     * @var        ObjectCollection|ChildComment[] Collection to store aggregation of ChildComment objects.
     */
    protected $collComments;
    protected $collCommentsPartial;

    /**
     * @var        ObjectCollection|ChildCommentUserEntry[] Collection to store aggregation of ChildCommentUserEntry objects.
     */
    protected $collCommentUserEntries;
    protected $collCommentUserEntriesPartial;

    /**
     * @var        ObjectCollection|ChildThread[] Collection to store aggregation of ChildThread objects.
     */
    protected $collThreads;
    protected $collThreadsPartial;

    /**
     * @var        ObjectCollection|ChildThreadUserEntry[] Collection to store aggregation of ChildThreadUserEntry objects.
     */
    protected $collThreadUserEntries;
    protected $collThreadUserEntriesPartial;

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
     * @var ObjectCollection|ChildComment[]
     */
    protected $commentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCommentUserEntry[]
     */
    protected $commentUserEntriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildThread[]
     */
    protected $threadsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildThreadUserEntry[]
     */
    protected $threadUserEntriesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->deleted_flag = false;
        $this->blocked_flag = false;
    }

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [optionally formatted] temporal [deleted_date] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeletedDate($format = NULL)
    {
        if ($format === null) {
            return $this->deleted_date;
        } else {
            return $this->deleted_date instanceof \DateTime ? $this->deleted_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [blocked_date] column value.
     * 
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBlockedDate($format = NULL)
    {
        if ($format === null) {
            return $this->blocked_date;
        } else {
            return $this->blocked_date instanceof \DateTime ? $this->blocked_date->format($format) : null;
        }
    }

    /**
     * Get the [firstname] column value.
     * 
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get the [lastname] column value.
     * 
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get the [email] column value.
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [username] column value.
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [deleted_flag] column value.
     * 
     * @return boolean
     */
    public function getDeletedFlag()
    {
        return $this->deleted_flag;
    }

    /**
     * Get the [deleted_flag] column value.
     * 
     * @return boolean
     */
    public function isDeletedFlag()
    {
        return $this->getDeletedFlag();
    }

    /**
     * Get the [blocked_flag] column value.
     * 
     * @return boolean
     */
    public function getBlockedFlag()
    {
        return $this->blocked_flag;
    }

    /**
     * Get the [blocked_flag] column value.
     * 
     * @return boolean
     */
    public function isBlockedFlag()
    {
        return $this->getBlockedFlag();
    }

    /**
     * Get the [locale_id] column value.
     * 
     * @return string
     */
    public function getLocaleId()
    {
        return $this->locale_id;
    }

    /**
     * Get the [user_type_id] column value.
     * 
     * @return string
     */
    public function getUserTypeId()
    {
        return $this->user_type_id;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [creation_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setCreationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->creation_date !== null || $dt !== null) {
            if ($this->creation_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->creation_date->format("Y-m-d H:i:s")) {
                $this->creation_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATION_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setCreationDate()

    /**
     * Sets the value of [updated_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUpdatedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_date !== null || $dt !== null) {
            if ($this->updated_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_date->format("Y-m-d H:i:s")) {
                $this->updated_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedDate()

    /**
     * Sets the value of [deleted_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setDeletedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->deleted_date !== null || $dt !== null) {
            if ($this->deleted_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->deleted_date->format("Y-m-d H:i:s")) {
                $this->deleted_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_DELETED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletedDate()

    /**
     * Sets the value of [blocked_date] column to a normalized version of the date/time value specified.
     * 
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setBlockedDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->blocked_date !== null || $dt !== null) {
            if ($this->blocked_date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->blocked_date->format("Y-m-d H:i:s")) {
                $this->blocked_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_BLOCKED_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setBlockedDate()

    /**
     * Set the value of [firstname] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->firstname !== $v) {
            $this->firstname = $v;
            $this->modifiedColumns[UserTableMap::COL_FIRSTNAME] = true;
        }

        return $this;
    } // setFirstname()

    /**
     * Set the value of [lastname] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lastname !== $v) {
            $this->lastname = $v;
            $this->modifiedColumns[UserTableMap::COL_LASTNAME] = true;
        }

        return $this;
    } // setLastname()

    /**
     * Set the value of [email] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [username] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Sets the value of the [deleted_flag] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setDeletedFlag($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->deleted_flag !== $v) {
            $this->deleted_flag = $v;
            $this->modifiedColumns[UserTableMap::COL_DELETED_FLAG] = true;
        }

        return $this;
    } // setDeletedFlag()

    /**
     * Sets the value of the [blocked_flag] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * 
     * @param  boolean|integer|string $v The new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setBlockedFlag($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->blocked_flag !== $v) {
            $this->blocked_flag = $v;
            $this->modifiedColumns[UserTableMap::COL_BLOCKED_FLAG] = true;
        }

        return $this;
    } // setBlockedFlag()

    /**
     * Set the value of [locale_id] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLocaleId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->locale_id !== $v) {
            $this->locale_id = $v;
            $this->modifiedColumns[UserTableMap::COL_LOCALE_ID] = true;
        }

        if ($this->aLocale !== null && $this->aLocale->getId() !== $v) {
            $this->aLocale = null;
        }

        return $this;
    } // setLocaleId()

    /**
     * Set the value of [user_type_id] column.
     * 
     * @param string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setUserTypeId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_type_id !== $v) {
            $this->user_type_id = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_TYPE_ID] = true;
        }

        if ($this->aUserType !== null && $this->aUserType->getId() !== $v) {
            $this->aUserType = null;
        }

        return $this;
    } // setUserTypeId()

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
            if ($this->deleted_flag !== false) {
                return false;
            }

            if ($this->blocked_flag !== false) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('CreationDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->creation_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('UpdatedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('DeletedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->deleted_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('BlockedDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->blocked_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('DeletedFlag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->deleted_flag = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('BlockedFlag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->blocked_flag = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('LocaleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->locale_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('UserTypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_type_id = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
     * in case your model_propel changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aLocale !== null && $this->locale_id !== $this->aLocale->getId()) {
            $this->aLocale = null;
        }
        if ($this->aUserType !== null && $this->user_type_id !== $this->aUserType->getId()) {
            $this->aUserType = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aLocale = null;
            $this->aUserType = null;
            $this->collChannelUserEntries = null;

            $this->collComments = null;

            $this->collCommentUserEntries = null;

            $this->collThreads = null;

            $this->collThreadUserEntries = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->aLocale !== null) {
                if ($this->aLocale->isModified() || $this->aLocale->isNew()) {
                    $affectedRows += $this->aLocale->save($con);
                }
                $this->setLocale($this->aLocale);
            }

            if ($this->aUserType !== null) {
                if ($this->aUserType->isModified() || $this->aUserType->isNew()) {
                    $affectedRows += $this->aUserType->save($con);
                }
                $this->setUserType($this->aUserType);
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

            if ($this->commentsScheduledForDeletion !== null) {
                if (!$this->commentsScheduledForDeletion->isEmpty()) {
                    \CommentQuery::create()
                        ->filterByPrimaryKeys($this->commentsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->commentsScheduledForDeletion = null;
                }
            }

            if ($this->collComments !== null) {
                foreach ($this->collComments as $referrerFK) {
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

            if ($this->threadUserEntriesScheduledForDeletion !== null) {
                if (!$this->threadUserEntriesScheduledForDeletion->isEmpty()) {
                    \ThreadUserEntryQuery::create()
                        ->filterByPrimaryKeys($this->threadUserEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->threadUserEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collThreadUserEntries !== null) {
                foreach ($this->collThreadUserEntries as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'creation_date';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'updated_date';
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'deleted_date';
        }
        if ($this->isColumnModified(UserTableMap::COL_BLOCKED_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'blocked_date';
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'firstname';
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'lastname';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_FLAG)) {
            $modifiedColumns[':p' . $index++]  = 'deleted_flag';
        }
        if ($this->isColumnModified(UserTableMap::COL_BLOCKED_FLAG)) {
            $modifiedColumns[':p' . $index++]  = 'blocked_flag';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOCALE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'locale_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_type_id';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'deleted_date':                        
                        $stmt->bindValue($identifier, $this->deleted_date ? $this->deleted_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'blocked_date':                        
                        $stmt->bindValue($identifier, $this->blocked_date ? $this->blocked_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'firstname':                        
                        $stmt->bindValue($identifier, $this->firstname, PDO::PARAM_STR);
                        break;
                    case 'lastname':                        
                        $stmt->bindValue($identifier, $this->lastname, PDO::PARAM_STR);
                        break;
                    case 'email':                        
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'username':                        
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'password':                        
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'deleted_flag':
                        $stmt->bindValue($identifier, (int) $this->deleted_flag, PDO::PARAM_INT);
                        break;
                    case 'blocked_flag':
                        $stmt->bindValue($identifier, (int) $this->blocked_flag, PDO::PARAM_INT);
                        break;
                    case 'locale_id':                        
                        $stmt->bindValue($identifier, $this->locale_id, PDO::PARAM_STR);
                        break;
                    case 'user_type_id':                        
                        $stmt->bindValue($identifier, $this->user_type_id, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDeletedDate();
                break;
            case 4:
                return $this->getBlockedDate();
                break;
            case 5:
                return $this->getFirstname();
                break;
            case 6:
                return $this->getLastname();
                break;
            case 7:
                return $this->getEmail();
                break;
            case 8:
                return $this->getUsername();
                break;
            case 9:
                return $this->getPassword();
                break;
            case 10:
                return $this->getDeletedFlag();
                break;
            case 11:
                return $this->getBlockedFlag();
                break;
            case 12:
                return $this->getLocaleId();
                break;
            case 13:
                return $this->getUserTypeId();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCreationDate(),
            $keys[2] => $this->getUpdatedDate(),
            $keys[3] => $this->getDeletedDate(),
            $keys[4] => $this->getBlockedDate(),
            $keys[5] => $this->getFirstname(),
            $keys[6] => $this->getLastname(),
            $keys[7] => $this->getEmail(),
            $keys[8] => $this->getUsername(),
            $keys[9] => $this->getPassword(),
            $keys[10] => $this->getDeletedFlag(),
            $keys[11] => $this->getBlockedFlag(),
            $keys[12] => $this->getLocaleId(),
            $keys[13] => $this->getUserTypeId(),
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
        
        if ($result[$keys[3]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[3]];
            $result[$keys[3]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }
        
        if ($result[$keys[4]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[4]];
            $result[$keys[4]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }
        
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->aLocale) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'locale';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'locale';
                        break;
                    default:
                        $key = 'Locale';
                }
        
                $result[$key] = $this->aLocale->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserType) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userType';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user_type';
                        break;
                    default:
                        $key = 'UserType';
                }
        
                $result[$key] = $this->aUserType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->collComments) {
                
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
        
                $result[$key] = $this->collComments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collThreadUserEntries) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'threadUserEntries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'thread_user_entries';
                        break;
                    default:
                        $key = 'ThreadUserEntries';
                }
        
                $result[$key] = $this->collThreadUserEntries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
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
                $this->setDeletedDate($value);
                break;
            case 4:
                $this->setBlockedDate($value);
                break;
            case 5:
                $this->setFirstname($value);
                break;
            case 6:
                $this->setLastname($value);
                break;
            case 7:
                $this->setEmail($value);
                break;
            case 8:
                $this->setUsername($value);
                break;
            case 9:
                $this->setPassword($value);
                break;
            case 10:
                $this->setDeletedFlag($value);
                break;
            case 11:
                $this->setBlockedFlag($value);
                break;
            case 12:
                $this->setLocaleId($value);
                break;
            case 13:
                $this->setUserTypeId($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

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
            $this->setDeletedDate($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBlockedDate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setFirstname($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLastname($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setEmail($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setUsername($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPassword($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDeletedFlag($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setBlockedFlag($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setLocaleId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setUserTypeId($arr[$keys[13]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATION_DATE)) {
            $criteria->add(UserTableMap::COL_CREATION_DATE, $this->creation_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_DATE)) {
            $criteria->add(UserTableMap::COL_UPDATED_DATE, $this->updated_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_DATE)) {
            $criteria->add(UserTableMap::COL_DELETED_DATE, $this->deleted_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_BLOCKED_DATE)) {
            $criteria->add(UserTableMap::COL_BLOCKED_DATE, $this->blocked_date);
        }
        if ($this->isColumnModified(UserTableMap::COL_FIRSTNAME)) {
            $criteria->add(UserTableMap::COL_FIRSTNAME, $this->firstname);
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTNAME)) {
            $criteria->add(UserTableMap::COL_LASTNAME, $this->lastname);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_FLAG)) {
            $criteria->add(UserTableMap::COL_DELETED_FLAG, $this->deleted_flag);
        }
        if ($this->isColumnModified(UserTableMap::COL_BLOCKED_FLAG)) {
            $criteria->add(UserTableMap::COL_BLOCKED_FLAG, $this->blocked_flag);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOCALE_ID)) {
            $criteria->add(UserTableMap::COL_LOCALE_ID, $this->locale_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TYPE_ID)) {
            $criteria->add(UserTableMap::COL_USER_TYPE_ID, $this->user_type_id);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCreationDate($this->getCreationDate());
        $copyObj->setUpdatedDate($this->getUpdatedDate());
        $copyObj->setDeletedDate($this->getDeletedDate());
        $copyObj->setBlockedDate($this->getBlockedDate());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setLastname($this->getLastname());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setDeletedFlag($this->getDeletedFlag());
        $copyObj->setBlockedFlag($this->getBlockedFlag());
        $copyObj->setLocaleId($this->getLocaleId());
        $copyObj->setUserTypeId($this->getUserTypeId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChannelUserEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChannelUserEntry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCommentUserEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCommentUserEntry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getThreads() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addThread($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getThreadUserEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addThreadUserEntry($relObj->copy($deepCopy));
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
     * @return \User Clone of current object.
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
     * Declares an association between this object and a ChildLocale object.
     *
     * @param  ChildLocale $v
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setLocale(ChildLocale $v = null)
    {
        if ($v === null) {
            $this->setLocaleId(NULL);
        } else {
            $this->setLocaleId($v->getId());
        }

        $this->aLocale = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildLocale object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildLocale object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildLocale The associated ChildLocale object.
     * @throws PropelException
     */
    public function getLocale(ConnectionInterface $con = null)
    {
        if ($this->aLocale === null && (($this->locale_id !== "" && $this->locale_id !== null))) {
            $this->aLocale = ChildLocaleQuery::create()->findPk($this->locale_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aLocale->addUsers($this);
             */
        }

        return $this->aLocale;
    }

    /**
     * Declares an association between this object and a ChildUserType object.
     *
     * @param  ChildUserType $v
     * @return $this|\User The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserType(ChildUserType $v = null)
    {
        if ($v === null) {
            $this->setUserTypeId(NULL);
        } else {
            $this->setUserTypeId($v->getId());
        }

        $this->aUserType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUserType object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUserType object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUserType The associated ChildUserType object.
     * @throws PropelException
     */
    public function getUserType(ConnectionInterface $con = null)
    {
        if ($this->aUserType === null && (($this->user_type_id !== "" && $this->user_type_id !== null))) {
            $this->aUserType = ChildUserTypeQuery::create()->findPk($this->user_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserType->addUsers($this);
             */
        }

        return $this->aUserType;
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
        if ('Comment' == $relationName) {
            return $this->initComments();
        }
        if ('CommentUserEntry' == $relationName) {
            return $this->initCommentUserEntries();
        }
        if ('Thread' == $relationName) {
            return $this->initThreads();
        }
        if ('ThreadUserEntry' == $relationName) {
            return $this->initThreadUserEntries();
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $channelUserEntryRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collChannelUserEntries);
    }

    /**
     * Method called to associate a ChildChannelUserEntry object to this object
     * through the ChildChannelUserEntry foreign key attribute.
     *
     * @param  ChildChannelUserEntry $l ChildChannelUserEntry
     * @return $this|\User The current object (for fluent API support)
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
        $channelUserEntry->setUser($this);
    }

    /**
     * @param  ChildChannelUserEntry $channelUserEntry The ChildChannelUserEntry object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $channelUserEntry->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ChannelUserEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildChannelUserEntry[] List of ChildChannelUserEntry objects
     */
    public function getChannelUserEntriesJoinChannel(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChannelUserEntryQuery::create(null, $criteria);
        $query->joinWith('Channel', $joinBehavior);

        return $this->getChannelUserEntries($query, $con);
    }

    /**
     * Clears out the collComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addComments()
     */
    public function clearComments()
    {
        $this->collComments = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collComments collection loaded partially.
     */
    public function resetPartialComments($v = true)
    {
        $this->collCommentsPartial = $v;
    }

    /**
     * Initializes the collComments collection.
     *
     * By default this just sets the collComments collection to an empty array (like clearcollComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initComments($overrideExisting = true)
    {
        if (null !== $this->collComments && !$overrideExisting) {
            return;
        }
        $this->collComments = new ObjectCollection();
        $this->collComments->setModel('\Comment');
    }

    /**
     * Gets an array of ChildComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     * @throws PropelException
     */
    public function getComments(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                // return empty collection
                $this->initComments();
            } else {
                $collComments = ChildCommentQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCommentsPartial && count($collComments)) {
                        $this->initComments(false);

                        foreach ($collComments as $obj) {
                            if (false == $this->collComments->contains($obj)) {
                                $this->collComments->append($obj);
                            }
                        }

                        $this->collCommentsPartial = true;
                    }

                    return $collComments;
                }

                if ($partial && $this->collComments) {
                    foreach ($this->collComments as $obj) {
                        if ($obj->isNew()) {
                            $collComments[] = $obj;
                        }
                    }
                }

                $this->collComments = $collComments;
                $this->collCommentsPartial = false;
            }
        }

        return $this->collComments;
    }

    /**
     * Sets a collection of ChildComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $comments A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setComments(Collection $comments, ConnectionInterface $con = null)
    {
        /** @var ChildComment[] $commentsToDelete */
        $commentsToDelete = $this->getComments(new Criteria(), $con)->diff($comments);

        
        $this->commentsScheduledForDeletion = $commentsToDelete;

        foreach ($commentsToDelete as $commentRemoved) {
            $commentRemoved->setUser(null);
        }

        $this->collComments = null;
        foreach ($comments as $comment) {
            $this->addComment($comment);
        }

        $this->collComments = $comments;
        $this->collCommentsPartial = false;

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
    public function countComments(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCommentsPartial && !$this->isNew();
        if (null === $this->collComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collComments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getComments());
            }

            $query = ChildCommentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collComments);
    }

    /**
     * Method called to associate a ChildComment object to this object
     * through the ChildComment foreign key attribute.
     *
     * @param  ChildComment $l ChildComment
     * @return $this|\User The current object (for fluent API support)
     */
    public function addComment(ChildComment $l)
    {
        if ($this->collComments === null) {
            $this->initComments();
            $this->collCommentsPartial = true;
        }

        if (!$this->collComments->contains($l)) {
            $this->doAddComment($l);
        }

        return $this;
    }

    /**
     * @param ChildComment $comment The ChildComment object to add.
     */
    protected function doAddComment(ChildComment $comment)
    {
        $this->collComments[]= $comment;
        $comment->setUser($this);
    }

    /**
     * @param  ChildComment $comment The ChildComment object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeComment(ChildComment $comment)
    {
        if ($this->getComments()->contains($comment)) {
            $pos = $this->collComments->search($comment);
            $this->collComments->remove($pos);
            if (null === $this->commentsScheduledForDeletion) {
                $this->commentsScheduledForDeletion = clone $this->collComments;
                $this->commentsScheduledForDeletion->clear();
            }
            $this->commentsScheduledForDeletion[]= clone $comment;
            $comment->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Comments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsJoinCommentRelatedByThreadId(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('CommentRelatedByThreadId', $joinBehavior);

        return $this->getComments($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Comments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildComment[] List of ChildComment objects
     */
    public function getCommentsJoinThread(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentQuery::create(null, $criteria);
        $query->joinWith('Thread', $joinBehavior);

        return $this->getComments($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $commentUserEntryRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCommentUserEntries);
    }

    /**
     * Method called to associate a ChildCommentUserEntry object to this object
     * through the ChildCommentUserEntry foreign key attribute.
     *
     * @param  ChildCommentUserEntry $l ChildCommentUserEntry
     * @return $this|\User The current object (for fluent API support)
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
        $commentUserEntry->setUser($this);
    }

    /**
     * @param  ChildCommentUserEntry $commentUserEntry The ChildCommentUserEntry object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $commentUserEntry->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CommentUserEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCommentUserEntry[] List of ChildCommentUserEntry objects
     */
    public function getCommentUserEntriesJoinComment(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCommentUserEntryQuery::create(null, $criteria);
        $query->joinWith('Comment', $joinBehavior);

        return $this->getCommentUserEntries($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setThreads(Collection $threads, ConnectionInterface $con = null)
    {
        /** @var ChildThread[] $threadsToDelete */
        $threadsToDelete = $this->getThreads(new Criteria(), $con)->diff($threads);

        
        $this->threadsScheduledForDeletion = $threadsToDelete;

        foreach ($threadsToDelete as $threadRemoved) {
            $threadRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collThreads);
    }

    /**
     * Method called to associate a ChildThread object to this object
     * through the ChildThread foreign key attribute.
     *
     * @param  ChildThread $l ChildThread
     * @return $this|\User The current object (for fluent API support)
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
        $thread->setUser($this);
    }

    /**
     * @param  ChildThread $thread The ChildThread object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $thread->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Threads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildThread[] List of ChildThread objects
     */
    public function getThreadsJoinChannel(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildThreadQuery::create(null, $criteria);
        $query->joinWith('Channel', $joinBehavior);

        return $this->getThreads($query, $con);
    }

    /**
     * Clears out the collThreadUserEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addThreadUserEntries()
     */
    public function clearThreadUserEntries()
    {
        $this->collThreadUserEntries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collThreadUserEntries collection loaded partially.
     */
    public function resetPartialThreadUserEntries($v = true)
    {
        $this->collThreadUserEntriesPartial = $v;
    }

    /**
     * Initializes the collThreadUserEntries collection.
     *
     * By default this just sets the collThreadUserEntries collection to an empty array (like clearcollThreadUserEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initThreadUserEntries($overrideExisting = true)
    {
        if (null !== $this->collThreadUserEntries && !$overrideExisting) {
            return;
        }
        $this->collThreadUserEntries = new ObjectCollection();
        $this->collThreadUserEntries->setModel('\ThreadUserEntry');
    }

    /**
     * Gets an array of ChildThreadUserEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildThreadUserEntry[] List of ChildThreadUserEntry objects
     * @throws PropelException
     */
    public function getThreadUserEntries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadUserEntriesPartial && !$this->isNew();
        if (null === $this->collThreadUserEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collThreadUserEntries) {
                // return empty collection
                $this->initThreadUserEntries();
            } else {
                $collThreadUserEntries = ChildThreadUserEntryQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collThreadUserEntriesPartial && count($collThreadUserEntries)) {
                        $this->initThreadUserEntries(false);

                        foreach ($collThreadUserEntries as $obj) {
                            if (false == $this->collThreadUserEntries->contains($obj)) {
                                $this->collThreadUserEntries->append($obj);
                            }
                        }

                        $this->collThreadUserEntriesPartial = true;
                    }

                    return $collThreadUserEntries;
                }

                if ($partial && $this->collThreadUserEntries) {
                    foreach ($this->collThreadUserEntries as $obj) {
                        if ($obj->isNew()) {
                            $collThreadUserEntries[] = $obj;
                        }
                    }
                }

                $this->collThreadUserEntries = $collThreadUserEntries;
                $this->collThreadUserEntriesPartial = false;
            }
        }

        return $this->collThreadUserEntries;
    }

    /**
     * Sets a collection of ChildThreadUserEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $threadUserEntries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setThreadUserEntries(Collection $threadUserEntries, ConnectionInterface $con = null)
    {
        /** @var ChildThreadUserEntry[] $threadUserEntriesToDelete */
        $threadUserEntriesToDelete = $this->getThreadUserEntries(new Criteria(), $con)->diff($threadUserEntries);

        
        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->threadUserEntriesScheduledForDeletion = clone $threadUserEntriesToDelete;

        foreach ($threadUserEntriesToDelete as $threadUserEntryRemoved) {
            $threadUserEntryRemoved->setUser(null);
        }

        $this->collThreadUserEntries = null;
        foreach ($threadUserEntries as $threadUserEntry) {
            $this->addThreadUserEntry($threadUserEntry);
        }

        $this->collThreadUserEntries = $threadUserEntries;
        $this->collThreadUserEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ThreadUserEntry objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ThreadUserEntry objects.
     * @throws PropelException
     */
    public function countThreadUserEntries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadUserEntriesPartial && !$this->isNew();
        if (null === $this->collThreadUserEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collThreadUserEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getThreadUserEntries());
            }

            $query = ChildThreadUserEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collThreadUserEntries);
    }

    /**
     * Method called to associate a ChildThreadUserEntry object to this object
     * through the ChildThreadUserEntry foreign key attribute.
     *
     * @param  ChildThreadUserEntry $l ChildThreadUserEntry
     * @return $this|\User The current object (for fluent API support)
     */
    public function addThreadUserEntry(ChildThreadUserEntry $l)
    {
        if ($this->collThreadUserEntries === null) {
            $this->initThreadUserEntries();
            $this->collThreadUserEntriesPartial = true;
        }

        if (!$this->collThreadUserEntries->contains($l)) {
            $this->doAddThreadUserEntry($l);
        }

        return $this;
    }

    /**
     * @param ChildThreadUserEntry $threadUserEntry The ChildThreadUserEntry object to add.
     */
    protected function doAddThreadUserEntry(ChildThreadUserEntry $threadUserEntry)
    {
        $this->collThreadUserEntries[]= $threadUserEntry;
        $threadUserEntry->setUser($this);
    }

    /**
     * @param  ChildThreadUserEntry $threadUserEntry The ChildThreadUserEntry object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeThreadUserEntry(ChildThreadUserEntry $threadUserEntry)
    {
        if ($this->getThreadUserEntries()->contains($threadUserEntry)) {
            $pos = $this->collThreadUserEntries->search($threadUserEntry);
            $this->collThreadUserEntries->remove($pos);
            if (null === $this->threadUserEntriesScheduledForDeletion) {
                $this->threadUserEntriesScheduledForDeletion = clone $this->collThreadUserEntries;
                $this->threadUserEntriesScheduledForDeletion->clear();
            }
            $this->threadUserEntriesScheduledForDeletion[]= clone $threadUserEntry;
            $threadUserEntry->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related ThreadUserEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildThreadUserEntry[] List of ChildThreadUserEntry objects
     */
    public function getThreadUserEntriesJoinThread(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildThreadUserEntryQuery::create(null, $criteria);
        $query->joinWith('Thread', $joinBehavior);

        return $this->getThreadUserEntries($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aLocale) {
            $this->aLocale->removeUser($this);
        }
        if (null !== $this->aUserType) {
            $this->aUserType->removeUser($this);
        }
        $this->id = null;
        $this->creation_date = null;
        $this->updated_date = null;
        $this->deleted_date = null;
        $this->blocked_date = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->email = null;
        $this->username = null;
        $this->password = null;
        $this->deleted_flag = null;
        $this->blocked_flag = null;
        $this->locale_id = null;
        $this->user_type_id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model_propel objects or collections of model_propel objects.
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
            if ($this->collComments) {
                foreach ($this->collComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCommentUserEntries) {
                foreach ($this->collCommentUserEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collThreads) {
                foreach ($this->collThreads as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collThreadUserEntries) {
                foreach ($this->collThreadUserEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collChannelUserEntries = null;
        $this->collComments = null;
        $this->collCommentUserEntries = null;
        $this->collThreads = null;
        $this->collThreadUserEntries = null;
        $this->aLocale = null;
        $this->aUserType = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
