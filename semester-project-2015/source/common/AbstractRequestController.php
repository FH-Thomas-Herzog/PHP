<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:35 AM
 */

namespace source\common;

use source\view\controller\ActionController;
use source\view\controller\SecurityController;
use source\view\controller\SessionController;
use source\view\controller\ViewController;

abstract class AbstractRequestController extends BaseObject
{

    protected $sessionCtrl;
    protected $securityCtrl;
    protected $postRequest;
    protected $viewId;
    protected $actionId;

    public function __construct()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $this->postRequest = false;
        } else {
            $this->postRequest = true;
        }
        $this->viewId = $this->getParameter(ViewController::$VIEW_ID);
        $this->actionId = $this->getParameter(ActionController::$ACTION_ID);
        $this->sessionCtrl = SessionController::getInstance();
        $this->securityCtrl = SecurityController::getInstance();
    }

    public abstract function handleAction();

    public abstract function prepareView($nextView);

    protected function getParameter($name)
    {
        if ($this->postRequest) {
            if (isset($_POST[$name])) {
                return $_POST[$name];
            }
        } else {
            if (isset($_GET[$name])) {
                return $_GET[$name];
            }
        }
    }
}