<?php

namespace Map;

use \User;
use \UserQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the id field
     */
    const COL_ID = 'user.id';

    /**
     * the column name for the creation_date field
     */
    const COL_CREATION_DATE = 'user.creation_date';

    /**
     * the column name for the updated_date field
     */
    const COL_UPDATED_DATE = 'user.updated_date';

    /**
     * the column name for the deleted_date field
     */
    const COL_DELETED_DATE = 'user.deleted_date';

    /**
     * the column name for the blocked_date field
     */
    const COL_BLOCKED_DATE = 'user.blocked_date';

    /**
     * the column name for the firstname field
     */
    const COL_FIRSTNAME = 'user.firstname';

    /**
     * the column name for the lastname field
     */
    const COL_LASTNAME = 'user.lastname';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'user.email';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'user.username';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'user.password';

    /**
     * the column name for the deleted_flag field
     */
    const COL_DELETED_FLAG = 'user.deleted_flag';

    /**
     * the column name for the blocked_flag field
     */
    const COL_BLOCKED_FLAG = 'user.blocked_flag';

    /**
     * the column name for the locale_id field
     */
    const COL_LOCALE_ID = 'user.locale_id';

    /**
     * the column name for the user_type_id field
     */
    const COL_USER_TYPE_ID = 'user.user_type_id';

    /**
     * The default string format for model_propel objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'CreationDate', 'UpdatedDate', 'DeletedDate', 'BlockedDate', 'Firstname', 'Lastname', 'Email', 'Username', 'Password', 'DeletedFlag', 'BlockedFlag', 'LocaleId', 'UserTypeId', ),
        self::TYPE_CAMELNAME     => array('id', 'creationDate', 'updatedDate', 'deletedDate', 'blockedDate', 'firstname', 'lastname', 'email', 'username', 'password', 'deletedFlag', 'blockedFlag', 'localeId', 'userTypeId', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_CREATION_DATE, UserTableMap::COL_UPDATED_DATE, UserTableMap::COL_DELETED_DATE, UserTableMap::COL_BLOCKED_DATE, UserTableMap::COL_FIRSTNAME, UserTableMap::COL_LASTNAME, UserTableMap::COL_EMAIL, UserTableMap::COL_USERNAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_DELETED_FLAG, UserTableMap::COL_BLOCKED_FLAG, UserTableMap::COL_LOCALE_ID, UserTableMap::COL_USER_TYPE_ID, ),
        self::TYPE_FIELDNAME     => array('id', 'creation_date', 'updated_date', 'deleted_date', 'blocked_date', 'firstname', 'lastname', 'email', 'username', 'password', 'deleted_flag', 'blocked_flag', 'locale_id', 'user_type_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'CreationDate' => 1, 'UpdatedDate' => 2, 'DeletedDate' => 3, 'BlockedDate' => 4, 'Firstname' => 5, 'Lastname' => 6, 'Email' => 7, 'Username' => 8, 'Password' => 9, 'DeletedFlag' => 10, 'BlockedFlag' => 11, 'LocaleId' => 12, 'UserTypeId' => 13, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'creationDate' => 1, 'updatedDate' => 2, 'deletedDate' => 3, 'blockedDate' => 4, 'firstname' => 5, 'lastname' => 6, 'email' => 7, 'username' => 8, 'password' => 9, 'deletedFlag' => 10, 'blockedFlag' => 11, 'localeId' => 12, 'userTypeId' => 13, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_CREATION_DATE => 1, UserTableMap::COL_UPDATED_DATE => 2, UserTableMap::COL_DELETED_DATE => 3, UserTableMap::COL_BLOCKED_DATE => 4, UserTableMap::COL_FIRSTNAME => 5, UserTableMap::COL_LASTNAME => 6, UserTableMap::COL_EMAIL => 7, UserTableMap::COL_USERNAME => 8, UserTableMap::COL_PASSWORD => 9, UserTableMap::COL_DELETED_FLAG => 10, UserTableMap::COL_BLOCKED_FLAG => 11, UserTableMap::COL_LOCALE_ID => 12, UserTableMap::COL_USER_TYPE_ID => 13, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'creation_date' => 1, 'updated_date' => 2, 'deleted_date' => 3, 'blocked_date' => 4, 'firstname' => 5, 'lastname' => 6, 'email' => 7, 'username' => 8, 'password' => 9, 'deleted_flag' => 10, 'blocked_flag' => 11, 'locale_id' => 12, 'user_type_id' => 13, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\User');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('creation_date', 'CreationDate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_date', 'UpdatedDate', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('deleted_date', 'DeletedDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('blocked_date', 'BlockedDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', true, 20, null);
        $this->addColumn('lastname', 'Lastname', 'VARCHAR', true, 20, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 100, null);
        $this->addColumn('username', 'Username', 'VARCHAR', true, 50, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('deleted_flag', 'DeletedFlag', 'BOOLEAN', true, 1, false);
        $this->addColumn('blocked_flag', 'BlockedFlag', 'BOOLEAN', true, 1, false);
        $this->addForeignKey('locale_id', 'LocaleId', 'VARCHAR', 'locale', 'id', true, 5, null);
        $this->addForeignKey('user_type_id', 'UserTypeId', 'VARCHAR', 'user_type', 'id', true, 20, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Locale', '\\Locale', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':locale_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('UserType', '\\UserType', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_type_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('ChannelUserEntry', '\\ChannelUserEntry', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'ChannelUserEntries', false);
        $this->addRelation('Comment', '\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Comments', false);
        $this->addRelation('CommentUserEntry', '\\CommentUserEntry', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'CommentUserEntries', false);
        $this->addRelation('Thread', '\\Thread', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':owner_user_id',
    1 => ':id',
  ),
), null, null, 'Threads', false);
        $this->addRelation('ThreadUserEntry', '\\ThreadUserEntry', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'ThreadUserEntries', false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }
    
    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();
    
        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_CREATION_DATE);
            $criteria->addSelectColumn(UserTableMap::COL_UPDATED_DATE);
            $criteria->addSelectColumn(UserTableMap::COL_DELETED_DATE);
            $criteria->addSelectColumn(UserTableMap::COL_BLOCKED_DATE);
            $criteria->addSelectColumn(UserTableMap::COL_FIRSTNAME);
            $criteria->addSelectColumn(UserTableMap::COL_LASTNAME);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_USERNAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_DELETED_FLAG);
            $criteria->addSelectColumn(UserTableMap::COL_BLOCKED_FLAG);
            $criteria->addSelectColumn(UserTableMap::COL_LOCALE_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_TYPE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.creation_date');
            $criteria->addSelectColumn($alias . '.updated_date');
            $criteria->addSelectColumn($alias . '.deleted_date');
            $criteria->addSelectColumn($alias . '.blocked_date');
            $criteria->addSelectColumn($alias . '.firstname');
            $criteria->addSelectColumn($alias . '.lastname');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.deleted_flag');
            $criteria->addSelectColumn($alias . '.blocked_flag');
            $criteria->addSelectColumn($alias . '.locale_id');
            $criteria->addSelectColumn($alias . '.user_type_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \User) { // it's a model_propel object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
