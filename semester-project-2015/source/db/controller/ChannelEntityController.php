<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\DbException;

class ChannelEntityController extends AbstractEntityController
{
    private static $SQL_CHECK_CHANNEL_BY_TITLE = "SELECT id FROM channel WHERE UPPER(title) = UPPER(?) AND deleted_flag = 0";

    private static $SQL_INSERT_CHANNEL = "INSERT INTO channel (title, description) VALUES (?,?)";

    private static $SQL_DELETE_CHANNEL = "DELETE FROM channel WHERE channel_id = ?";

    private static $SQL_SELECT_ASSIGNED_CHANNELS_WITH_MSG_COUNT =
        "SELECT COUNT(cm.id) AS msgCount, c.id, c.title, c.description, cue.favorite_flag AS favorite FROM channel c " .
        " LEFT OUTER JOIN channel_message cm ON cm.channel_id = c.id " .
        " INNER JOIN channel_user_entry cue ON cue.channel_id = c.id " .
        " WHERE cue.user_id = ?" .
        " GROUP BY c.id, c.title, c.description" .
        " ORDER BY cue.favorite_flag DESC, c.creation_date desc";

    private static $SQL_SELECT_UNASSIGNED_CHANNELS_WITH_MSG_COUNT =
        "SELECT c.id, c.title, c.description FROM channel c " .
        " LEFT OUTER JOIN channel_message cm ON cm.channel_id = c.id " .
        " LEFT JOIN channel_user_entry cue ON cue.channel_id = c.id " .
        " WHERE ((cue.user_id IS NULL) OR (cue.user_id != ?)) " .
        " GROUP BY c.title, c.description" .
        " ORDER BY c.creation_date desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function getAll()
    {

    }

    public function getAssignedChannelsWithMsgCount($userId)
    {
        parent::open();

        $res = array();
        $stmt = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_SELECT_ASSIGNED_CHANNELS_WITH_MSG_COUNT);
            $p1 = (integer)$userId;
            $stmt->bind_param("s", $p1);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = null;
            while ($data = $result->fetch_object()) {
                $res[] = $data;
            }
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_SELECT_ASSIGNED_CHANNELS_WITH_MSG_COUNT . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    public function getUnassignedChannels($userId)
    {
        parent::open();

        $res = array();
        $stmt = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_SELECT_UNASSIGNED_CHANNELS_WITH_MSG_COUNT);
            $p1 = (integer)$userId;
            $stmt->bind_param("s", $p1);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = null;
            while ($data = $result->fetch_object()) {
                $res[] = $data;
            }
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_SELECT_UNASSIGNED_CHANNELS_WITH_MSG_COUNT . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    /**
     * Answers the question if a channel with the given title already exists.
     *
     * @param string $title the channel title
     * @return boolean true if a channel with this title exists false otherwise
     * @throws DbException if an error occurs
     */
    public function isChannelExistingWithTitle($title)
    {

        parent::open();

        $res = false;
        $stmtRes = null;
        $stmt = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHECK_CHANNEL_BY_TITLE);
            $p1 = (string)$title;
            $stmt->bind_param("s", $p1);
            $success = $stmt->execute();
            $stmtRes = $stmt->get_result();
            $res = ($stmtRes->num_rows != 0);
        } catch (\Exception $e) {
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_CHANNEL_BY_TITLE . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->free_result();
                $stmt->close();
            }
            parent::close();
        }

        return $res;
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    public function persist(array $args)
    {

        parent::open();

        $stmt = null;
        $res = null;

        try {
            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL);
            $p1 = $args["title"];
            $p2 = $args["description"];
            $stmt->bind_param("ss", $p1, $p2);

            parent::startTx();
            $stmt->execute();
            parent::commit();
            $res = $stmt->insert_id;
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
        return $res;
    }


    public
    function update($entity)
    {
        // TODO: Implement update() method.
    }

    public
    function delete($id)
    {
        parent::open();

        $stmt = null;
        $res = 0;

        try {
            $stmt = parent::prepareStatement(self::$SQL_DELETE_CHANNEL);
            $p1 = $id;
            $stmt->bind_param("i", $p1);

            parent::startTx();
            $stmt->execute();
            parent::commit();
            $res = $stmt->affected_rows;
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_CHECK_ACTIVE_USER_BY_USERNAME . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
        return $res;
    }

}