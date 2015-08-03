<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/15/2015
 * Time: 8:47 PM
 */

namespace source\view\model;

use \source\common\BaseObject;
use \source\common\InternalErrorException;
use \source\common\utils\ObjectUtil;

/**
 * This class represents the user session model_propel, which holds all relevant information
 * about an logged user.
 * Class UserSessionModel
 * @package SCM4\View\Model
 */
class UserSessionModel extends BaseObject
{
    /**
     * The related user database model_propel
     * @var User
     */
    private $user;

    /**
     * Constructs this instance with the given user.
     * @param User|null $user the logged user
     * @throws InternalErrorException if the given user is null
     */
    public function __construct($user = null)
    {
        parent::__construct();
        ObjectUtil::requireSet($user, new InternalErrorException("Cannot instantiate UserSessionModel with null user"));

        $this->user = $user;
    }

    /**
     * Gets the backed user id.
     * @return integer the user id
     */
    public function getUserId()
    {
        return $this->getUser()->getId();
    }

    /**
     * Gets the backed user
     * @return User the backed user
     */
    public function getUser()
    {
        return $this->user;
    }
}