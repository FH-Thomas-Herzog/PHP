<?php

/**
 * Controller
 *
 * class handles POST requests and redirects
 * the client after processing
 * - demo of singleton pattern
 */
class Controller extends BaseObject
{
    // static strings used in views

    const ACTION_PARAM = 'action';
    const ALLOWED_REQUEST_METHOD = 'POST';
    const PAGE = 'page';
    const CC_NAME = 'nameOnCard';
    const CC_NUMBER = 'cardNumber';
    const ACTION_ADD = 'addToCart';
    const ACTION_REMOVE = 'removeFromCart';
    const ACTION_ORDER = 'placeOrder';
    const ACTION_LOGIN = 'login';
    const USR_NAME = 'userName';
    const USR_PASSWORD = 'password';
    const ACTION_LOGOUT = 'logout';

    private static $instance = false;

    /**
     *
     * @return Controller
     */
    public static function getInstance()
    {

        if (!self::$instance) {
            self::$instance = new Controller();
        }
        return self::$instance;
    }

    private function __construct()
    {

    }

    /**
     *
     * processes POST requests and redirects client depending on selected
     * action
     *
     * @return bool
     * @throws Exception
     */
    public function invokePostAction()
    {
        if ($_SERVER['REQUEST_METHOD'] != self::ALLOWED_REQUEST_METHOD) {
            throw new Exception('Controller can only handle ' . self::ALLOWED_REQUEST_METHOD . '" requests');
            return null;
        } else if (isset($_SERVER[self::ACTION_PARAM])) {
            throw new Exception('No action parameter set ' . self::ACTION_PARAM . '');
            return null;
        }

        $action = $_REQUEST[self::ACTION_PARAM];
        echo "action" . $action;

        switch ($action) {
            case self::ACTION_ADD:
                $bookId = isset($_REQUEST['bookId']) ? $_REQUEST['bookId'] : -1;
                if ($bookId > 0) {
                    ShoppingCard::add($bookId);
                }
                Util::redirect();
                break;
            case self::ACTION_LOGIN:
                break;
            case self::ACTION_LOGOUT:
                break;
            case self::ACTION_ORDER:
                break;
            case self::ACTION_REMOVE:
                break;
            default:
                break;
        }
    }

    /**
     *
     * @param string $nameOnCard
     * @param integer $cardNumber
     * @return bool
     */
    protected function processCheckout($nameOnCard = null, $cardNumber = null)
    {

    }

    /**
     *
     * @param array $errors : optional assign it to
     * @param string $target : url for redirect of the request
     */
    protected function forwardRequest(array $errors = null, $target = null)
    {

    }

}
