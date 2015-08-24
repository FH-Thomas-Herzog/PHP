<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:35 AM
 */

namespace source\common;

use source\view\controller\SecurityController;
use source\view\controller\SessionController;

/**
 * This class is the base class for a concrete view controller.
 *
 * Class AbstractRequestController
 * @package source\common
 */
abstract class AbstractViewController extends BaseObject
{

    protected $sessionCtrl;
    protected $securityCtrl;
    protected $postRequest;
    protected $jsonResult;
    protected $viewId;
    protected $actionId;

    /**
     * Constructs this instance by initializing backed members.
     */
    public function __construct()
    {
        // Set flag which indicates which method has been used
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->postRequest = false;
        } else {
            $this->postRequest = true;
        }
        // Set the view id which caused this request
        $this->viewId = $this->getParameter("viewId");
        // Sets the action id which was caused by the requesting view
        $this->actionId = $this->getParameter("actionId");
        // Gets the session controller singleton instance
        $this->sessionCtrl = SessionController::getInstance();
        // Gets the security controller singleton instance
        $this->securityCtrl = SecurityController::getInstance();
        // Check if json result is intended
        $this->jsonResult = (!empty($this->getParameter("ajax")));
    }

    /**
     * Predefined function which is used for handling the requested action.
     *
     * @return mixed any intended result
     */
    public abstract function handleAction();

    /**
     * Predefined function which is used to prepare the view.
     * @param string $nextView the id of the view to prepare
     * @return mixed any intended result
     */
    public abstract function prepareView($nextView);

    /**
     * Utility method which gets an parameter either from GET or POSt request depending on the
     * used request method
     *
     * @param string $name the request parameter name
     * @return mixed the value of the request parameter or null if request parameter could not be found
     */
    protected function getParameter($name)
    {
        $result = null;

        if ($this->postRequest) {
            if (isset($_POST[$name])) {
                $result = $_POST[$name];
            }
        } else {
            if (isset($_GET[$name])) {
                $result = $_GET[$name];
            }
        }

        return $result;
    }
}