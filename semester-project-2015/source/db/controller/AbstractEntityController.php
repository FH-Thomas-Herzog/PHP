<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 4:45 PM
 */

namespace source\db\controller;


use source\common\BaseObject;
use source\common\InternalErrorException;

/**
 * This class is the base class for entity controllers which are the accessor to the a single table.
 *
 * Class AbstractEntityController
 * @package source\db\controller
 */
abstract class AbstractEntityController extends BaseObject
{

    /**
     * The static connections arguments used by mysqli.
     * @var array
     */
    protected static $CONNECTION_PARAMS = array(
        "localhost",
        "fh_2015_scm4",
        "fh_2015_scm4",
        "fh_2015_scm4_s1310307011",
        3306
    );

    /**
     * The mysqli instance unique for a single entity controller instance.
     * @var null
     */
    private $mysqli = null;

    /**
     * Constructor which does nothing but could perform common initialization work.
     */
    public function __construct()
    {
    }

    /**
     * Predefined function for getting a single row by its id.
     *
     * @param mixed $id the id of entity controller managed table. Type depends on table id type.
     * @return mixed the result representing the table row
     */
    public abstract function getById($id);

    /**
     * Deletes a table row of the entity controller controlled table.
     *
     * @param mixed $id the id of entity controller managed table. Type depends on table id type.
     * @return mixed Any result intended to be returned
     */
    public abstract function deleteById($id);

    /**
     * Persists an entity controller controlled table row.
     *
     * @param array $args the array holding the table column values
     * @return mixed any intended result
     */
    public abstract function persist(array $args);

    /**
     * Updates an entity controller controlled table row.
     *
     * @param array $args the array holding the id along with the to update column values
     * @return mixed any intended result
     */
    public abstract function update(array $args);

    /**
     * Prepares an sql statement for the given sql query.
     *
     * @param string $sql the sql statement used by teh prepared statement
     * @return PreparedStatement the created prepared statement
     * @throws InternalErrorException if the preparation of the statement fails for any reason
     */
    protected function prepareStatement($sql)
    {
        $stmt = $this->getMysqli()->prepare((string)$sql);
        if ($stmt === false) {
            throw new InternalErrorException("Prepare statement returned false. Maybe the syntax of your sql is wrong");
        }
        return $stmt;
    }

    /**
     * Allows access to the underlying mysqli instance used by the concrete entity controller instance.
     *
     * @return the backed myslqi instance
     * @throws InternalErrorException if the mysqli instance is not set yet
     */
    protected function getMysqli()
    {
        if (isset($this->mysqli)) {
            return $this->mysqli;
        }

        throw new InternalErrorException("Mysqli not initialized");
    }

    /**
     * Opens a connection tot he database by creating an mysqli instance.
     * Any former open connection will be closed before the mysqli instance gets created.
     *
     * @throws InternalErrorException if the creation of the myslqi instance fails for any reason
     * @see $this->reconnect()
     */
    protected function open()
    {
        if (!isset($this->mysqli)) {
            $this->reconnect();
        }
    }

    /**
     * Close the connection to the database iif an connection exists, or in other words an myslqi instance is set.
     */
    protected function close()
    {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
            $this->mysqli = null;
        }
    }

    /**
     * Starts an transaction an sets the autocommit flag.
     *
     * @param bool|false $autoCommit true if autocommit shall be used false otherwise and as default
     * @throws InternalErrorException if the transaction can not be started.
     */
    protected function startTx($autoCommit = false)
    {
        $this->getMysqli()->autocommit($autoCommit);

        if (!$this->getMysqli()->begin_transaction()) {
            throw new InternalErrorException("Could not start transaction" . mysqli_error());
        }
    }

    /**
     * Commits the current transaction which is necessary if autocommit has been set to false which is default by this implementation.
     * Be notified that any occurring exception must be handled by the caller.
     */
    protected function commit()
    {
        $this->getMysqli()->commit();
    }

    /**
     * Rolls the current transaction back.
     * Be notified that any occurring exception must be handled by the caller.
     */
    protected function rollback()
    {
        $this->getMysqli()->rollback();
    }

    /**
     * This function reconnects to the database if an connection is already open, or creates an new one if no connection
     * is open.
     *
     * @throws InternalErrorException if the connection could not be obtained
     */
    private function reconnect()
    {
        // Close former opened connection
        $this->close();

        $this->mysqli = new \mysqli(
            self::$CONNECTION_PARAMS[0],
            self::$CONNECTION_PARAMS[1],
            self::$CONNECTION_PARAMS[2],
            self::$CONNECTION_PARAMS[3],
            self::$CONNECTION_PARAMS[4]
        );

        // Check if connection could be obtained
        if (mysqli_connect_error()) {
            throw new InternalErrorException("Could not obtain connection. error: " . mysqli_connect_error());
        }

        // Configure mysqli to report errors only
        if (!mysqli_report(MYSQLI_REPORT_ERROR)) {
            throw new InternalErrorException("Could not configure mysqli report mode");
        }
    }
}