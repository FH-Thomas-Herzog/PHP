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
use source\view\controller\PoolController;

abstract class AbstractEntityController extends BaseObject
{

    protected static $CONNECTION_PARAMS = array(
        "localhost",
        "fh_2015_scm4",
        "fh_2015_scm4",
        "fh_2015_scm4_s1310307011",
        3306
    );

    private $mysqli = null;

    public function __construct($mysqli = null)
    {
        if ($mysqli) {
            $this->mysqli = $mysqli;
        }
    }

    public abstract function getById($id);

    public abstract function deleteById($id);

    public abstract function persist(array $args);

    public abstract function update(array $args);

    protected function prepareStatement($sql)
    {
        $stmt = $this->getMysqli()->prepare((string)$sql);
        if ($stmt === false) {
            throw new InternalErrorException("Prepare statement returned false. Maybe the syntax of your sql is wrong");
        }
        return $stmt;
    }

    protected function getMysqli()
    {
        if (isset($this->mysqli)) {
            return $this->mysqli;
        }

        throw new InternalErrorException("Mysqli not initialized");
    }

    protected function open()
    {
        if (!isset($this->mysqli)) {
            $this->reconnect();
        }
    }

    protected function close()
    {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
            $this->mysqli = null;
        }
    }

    protected function startTx($autoCommit = false)
    {
        if (!$this->getMysqli()->begin_transaction()) {
            throw new InternalErrorException("Could not start transaction" . mysqli_error());
        }
        $this->getMysqli()->autocommit($autoCommit);
    }

    protected function commit()
    {
        $this->getMysqli()->commit();
    }

    protected function rollback()
    {
        $this->getMysqli()->rollback();
    }

    /**
     * This method creates a new connection if an connection already exists or creates the first one.
     *
     * @param bool|false $cache true if the connection shall be cached
     * @throws InternalErrorException if the connection could not be obtained
     */
    private function reconnect()
    {
        $this->close();

        $params = self::$CONNECTION_PARAMS;
        $this->mysqli = new \mysqli(
            $params[0],
            $params[1],
            $params[2],
            $params[3],
            $params[4]
        );
        if (!mysqli_report(MYSQLI_REPORT_ERROR)) {
            throw new InternalErrorException("Could not configure mysqli report mode");
        }

        if (mysqli_connect_error()) {
            throw new InternalErrorException("Could not obtain connection. error: " . mysqli_connect_error());
        }
    }
}