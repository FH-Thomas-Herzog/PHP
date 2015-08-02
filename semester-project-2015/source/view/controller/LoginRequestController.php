<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 9:37 AM
 */

namespace source\view\controller;


use source\common\AbstractRequestController;
use source\common\InternalErrorException;

class LoginRequestController extends AbstractRequestController
{
    private $pool;
    private $viewController;

    public function __construct(Pool $pool)
    {
        parent::__construct();
        $this->pool = $pool;
    }

    public function handleRequest()
    {
        parent::handleRequest();

        if (!StringUtil::compare($this->viewId, TemplateController::$VIEW_LOGIN)) {
            throw new InternalErrorException("Action: '" . $this->actionId . "' called from invalid view: '" . $this->viewId . "''");
        }

        $username = parent::getParameter("username");
        $password = parent::getParameter("password");

        // TODO: Login user
    }
}