<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 5:41 PM
 */

namespace source\db\controller;


use source\common\InternalErrorException;

class ChannelEntityController extends AbstractEntityController
{
    private static $SQL_CHECK_CHANNEL_BY_TITLE = "SELECT id FROM channel WHERE UPPER(title) = UPPER(?) AND deleted_flag = 0";

    private static $SQL_INSERT_CHANNEL = "INSERT INTO channel (title, description) VALUES (?,?)";

    private static $SQL_INSERT_CHANNEL_USER_ENTRY = "INSERT INTO channel_user_entry (user_id, channel_id, favorite_flag) VALUES (?,?,?)";

    private static $SQL_UPDATE_RESET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=0 WHERE favorite_flag=1 AND user_id=?";

    private static $SQL_UPDATE_SET_FAVORITE_CHANNEL = "UPDATE channel_user_entry SET favorite_flag=1 WHERE channel_id=? AND user_id=?";

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

    /**
     * Answers the question if a channel with the given title already exists.
     *
     * @param string $title the channel title
     * @return boolean true if a channel with this title exists false otherwise
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
            if (!$success) {
                throw new \Exception("Could not check for already existing channel");
            }
            $stmtRes = $stmt->get_result();
            $res = ($stmtRes->num_rows != 0);
        } catch (\Exception $e) {

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

    public function setFavoriteChannel($userId = null, $channelId = null, $favorite = false, $joinedTx = false)
    {
        $joinTx = (boolean)$joinedTx;
        $setFavorite = (boolean)$favorite;
        if (!$joinTx) {
            parent::startTx();
        }

        $stmtReset = null;
        $stmtSet = null;
        $res = false;

        try {
            $stmtReset = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
            $p1 = (integer)$userId;
            $stmtReset->bind_param("s", $p1);
            $res = $stmtReset->execute();
            if (!$res) {
                throw new \Exception("Could not reset channel user entries favorite flags");
            }
            if ($setFavorite) {
                $stmtSet = parent::prepareStatement(self::$SQL_UPDATE_SET_FAVORITE_CHANNEL);
                $p1 = (integer)$userId;
                $p2 = (integer)$channelId;
                $stmtSet->bind_param("ss", $p1, $p2);
                $res = $stmtSet->execute();
                if (!$res) {
                    throw new \Exception("Could not set favorite flag on channel user entries");
                }
            }
            if (!$joinedTx) {
                parent::commit();
            }
        } catch (\Exception $e) {

        } finally {
            if (isset($stmtReset)) {
                $stmtReset->close();
            }
            if (isset($stmtSet)) {
                $stmtSet->close();
            }
            if (!$joinTx) {
                parent::close();
            }
        }

        return $res;
    }

    public function persist(array $args)
    {

        parent::open();

        $stmtChannel = null;
        $stmtChannelEntry = null;
        $stmtResetChannelEntry = null;
        $res = false;

        try {
            $stmtChannel = parent::prepareStatement(self::$SQL_INSERT_CHANNEL);
            $stmtChannelEntry = parent::prepareStatement(self::$SQL_INSERT_CHANNEL_USER_ENTRY);

            $p1 = $args["title"];
            $p2 = $args["description"];
            $stmtChannel->bind_param("ss", $p1, $p2);
            parent::startTx();
            $res = $stmtChannel->execute();
            if (!$res) {
                throw new \Exception("Could not save channel! ");
            }

            $p3 = $args["userId"];
            $p4 = $stmtChannel->insert_id;
            $p5 = (integer)$args["favorite"];

            $stmtChannelEntry->bind_param("ssi", $p3, $p4, $p5);
            $res = $stmtChannelEntry->execute();
            if (!$res) {
                throw new \Exception("Could not save channel user entry");
            }
            // reset all other user entries and set favorite true on the current one
            if ($p5 === 1) {
                $stmtResetChannelEntry = parent::prepareStatement(self::$SQL_UPDATE_RESET_FAVORITE_CHANNEL);
                $p1 = (integer)$args["userId"];
                $stmtResetChannelEntry->bind_param("s", $p1);
                $res = $stmtResetChannelEntry->execute();
                if (!$res) {
                    throw new \Exception("Could not reset channel user entries favorite flags");
                }
            }
            parent::commit();
        } catch (\Exception $e) {
            parent::rollback();
        } finally {
            if (isset($stmtChannel)) {
                $stmtChannel->close();
            }
            if (isset($stmtChannelEntry)) {
                $stmtChannelEntry->close();
            }
            if (isset($stmtResetChannelEntry)) {
                $stmtResetChannelEntry->close();
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
    function delete($entity)
    {
        // TODO: Implement delete() method.
    }

}