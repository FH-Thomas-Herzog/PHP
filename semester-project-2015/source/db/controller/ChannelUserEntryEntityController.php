<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\DbException;

/**
 * This controller is the db accessor to the channel_user_entry table.
 *
 * Class ChannelUserEntryEntityController
 * @package source\db\controller
 */
class ChannelUserEntryEntityController extends AbstractEntityController
{

    public static $SQL_CHANNEL_USER_ENTRY_BY_ID = "SELECT * FROM channel_user_entry WHERE user_id = ? AND channel_id = ?";

    public static $SQL_INSERT_CHANNEL_USER_ENTRY = "INSERT INTO channel_user_entry (user_id, channel_id, favorite_flag) VALUES (?,?,?)";

    public static $SQL_UPDATE_RESET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=0 WHERE favorite_flag=1 AND user_id=?";

    private static $SQL_UPDATE_CHANNEL_USER_ENTRY = "UPDATE channel_user_entry SET favorite_flag=? WHERE user_id=? AND channel_id=?";

    private static $SQL_DELETE_CHANNEL_USER_ENTRY = "DELETE FROM channel_user_entry WHERE user_id = ? AND channel_id = ?";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets an channel user entry by it id.
     *
     * @param mixed $id the array holding the id of the channel user entry
     * @return stdClass the retrieved class or null if no result has been returned
     * @throws DbException if an error occurs
     */
    public function getById($id)
    {
        parent::open();

        $res = null;
        $stmt = null;
        $p1 = (integer)$id["userId"];
        $p2 = (integer)$id["channelId"];

        try {
            $stmt = parent::prepareStatement(self::$SQL_CHANNEL_USER_ENTRY_BY_ID);
            $stmt->bind_param("ii", $p1, $p2);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_object();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query '" . self::$SQL_CHANNEL_USER_ENTRY_BY_ID . "'" . PHP_EOL . "Error: '" . $e->getMessage());
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
     * Deletes an channel user entry by tis id.
     *
     * @param mixed $id the array holding the id of the channel user entry
     * @return bool true if the entry was deleted false otherwise.
     * @throws DbException if an error occurs
     */
    public function deleteById($id)
    {
        parent::open();

        $stmt = null;
        $result = false;

        try {
            $stmt = parent::prepareStatement(self::$SQL_DELETE_CHANNEL_USER_ENTRY);
            $p1 = (integer)$id["userId"];
            $p2 = (integer)$id["channelId"];
            $stmt->bind_param("ii", $p1, $p2);
            parent::startTx();
            $stmt->execute();
            $result = ($stmt->affected_rows == 1);
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_DELETE_CHANNEL_USER_ENTRY . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($stmtSet)) {
                $stmtSet->close();
            }
            parent::close();
        }

        return $result;
    }

    /**
     * Persists an channeel user entry entity.
     * It resets the currently set favorite flag if one exists and the new channel is marked as favorite.
     *
     * @param array $args the array holding the table column values
     * @throws DbException if an error occurs
     * @return nothing
     */
    public function persist(array $args)
    {

        parent::open();

        $stmt = null;

        try {
            $p1 = (integer)$args["userId"];
            $p2 = (integer)$args["channelId"];
            $p3 = (boolean)$args["favorite"];

            parent::startTx();

            // reset existing favorite flag
            if ($p3) {
                $stmt = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
                $stmt->bind_param("i", $p1);
                $stmt->execute();
            }

            $stmt = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_USER_ENTRY);
            $stmt->bind_param("iii", $p1, $p2, $p3);
            $stmt->execute();
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing query: '" . self::$SQL_INSERT_CHANNEL_USER_ENTRY . "''" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            parent::close();
        }
    }

    /**
     * Updates a channel user entry table entry.
     *
     * @param array $args the array holding the column values.
     * @return true if a row has been updated false otherwise;
     * @throws DbException if an error occurs
     */
    function update(array $args)
    {
        parent::open();

        $stmtReset = null;
        $stmtSet = null;
        $p1 = (integer)$args["favoriteFlag"];
        $p2 = (integer)$args["userId"];
        $p3 = (integer)$args["channelId"];
        $res = false;

        try {
            parent::startTx();

            if ($p1 == 1) {
                $stmtReset = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
                $stmtReset->bind_param("i", $p2);
                $stmtReset->execute();
            }
            $stmtSet = parent::prepareStatement(self::$SQL_UPDATE_CHANNEL_USER_ENTRY);
            $stmtSet->bind_param("iii", $p1, $p2, $p3);
            $stmtSet->execute();
            $res = ($stmtSet->affected_rows == 1);

            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
            throw new DbException("Error on executing " . __CLASS__ . "#setFavoriteChannel(userId, channelId)" . PHP_EOL . "Error: '" . $e->getMessage());
        } finally {
            if (isset($stmtReset)) {
                $stmtReset->close();
            }
            if (isset($stmtSet)) {
                $stmtSet->close();
            }
            parent::close();
        }

        return $res;
    }
}