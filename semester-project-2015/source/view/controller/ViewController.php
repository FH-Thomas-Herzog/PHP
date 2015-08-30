<?php
/**
 * Created by PhpStorm.
 * User: cchet
 * Date: 8/2/2015
 * Time: 1:15 PM
 */

namespace source\view\controller;


use source\common\AbstractViewController;
use source\common\InternalErrorException;
use source\common\utils\StringUtil;
use source\view\model\RequestControllerResult;
use Stash\Pool;

/**
 * This is the view controller which handles requested actions and renders the intended view.
 * Also it specifies all of the available views in the application.
 *
 * Class ViewController
 * @package source\view\controller
 */
class ViewController extends AbstractViewController
{
    public static $VIEW_INITIAL = "login";

    public static $VIEW_LOGIN = "login";

    public static $VIEW_START = "start";

    public static $VIEW_MAIN = "main";

    public static $VIEW_REGISTRATION = "registration";

    public static $PARTIAL_VIEW_REGISTRATION_SUCCESS = "partialRegistrationSuccess";

    public static $PARTIAL_VIEW_CHANNELS = "partialChannels";

    public static $PARTIAL_VIEW_NEW_CHANNEL = "partialNewChannel";

    public static $PARTIAL_VIEW_CHANNEL = "partialChannel";

    public static $PARTIAL_VIEW_CHANNEL_CHAT = "partialChannelChat";

    public static $REFRESH_ACTION = "refreshAction";

    private static $CACHE_DEFAULT = true;

    private static $RECREATE_CACHED_DEFAULT = false;

    private $pool;

    /**
     * Constructs this instance and sets the given pool and delegates to the base class so that common initialization can occur.
     *
     * @param Pool $pool the pool instance where the template render instance gets cached.
     * @throws InternalErrorException if no pool is given
     */
    public function __construct(Pool $pool)
    {
        parent::__construct();
        if (!isset($pool)) {
            throw new InternalErrorException("Pool null but needed");
        }
        $this->pool = $pool;
    }

    /**
     * Handles the request by handling the request and to render response.
     * This method is only contained here and is not defined in the AbstractViewController class.
     *
     * @return string the to return result either html or json.
     * @throws InternalErrorException if an action can not be performed because it is not supported for the current view,
     * or the next view cannot prepared because not supported by the current view, or if any other error occurs.
     */
    public function handleRequest()
    {
        $requestResult = "undefined reqeust result";

        $result = null;
        $html = null;
        try {
            // Handle intended action
            $result = $this->handleAction();
            // get cache config from action result.
            // If null then not defined by action result
            $cache = $this->isToCacheTemplate($result->args);
            $recreate = $this->isToRecreateTemplate($result->args);

            // render html
            if (!empty($result->nextView)) {
                $args = $this->prepareView($result->nextView);
                $html = "";
                if (isset($args)) {
                    // action result overrules prepareView pool configuration
                    $cache = (!isset($cache)) ? $this->isToCacheTemplate($args) : $cache;
                    $recreate = (!isset($recreate)) ? $this->isToRecreateTemplate($args) : $recreate;
                    // Default is to never cache anything if not configured
                    $html = $this->getTemplateController()->renderView($result->nextView, ((!isset($cache)) ? self::$CACHE_DEFAULT : $cache), (!isset($recreate) ? self::$RECREATE_CACHED_DEFAULT : $recreate), array_merge($result->args, $args));
                }
            }
        } catch (InternalErrorException $e) {
            $args = null;
            $nextView = null;
            if ($this->jsonResult) {
                $args = array(
                    "error" => true,
                    "message" => "Error on handling request",
                    "additionalMessage" => $e->getMessage(),
                    "messageType" => "danger"
                );
            }
            $result = new RequestControllerResult(false, $nextView, $args);
        }

        // handle html result request
        if (!$this->jsonResult) {
            $requestResult = $html;
        } // handle json result request
        else {
            // set html on json if a html was produced before.
            if (!empty($html)) {
                $result->args["html"] = $html;
            }
            $requestResult = json_encode($result->args);
        }

        return $requestResult;
    }

    /**
     * Handles the requested action on the current view.
     *
     * @return mixed|null|\source\view\model\RequestControllerResult the result returned by the view related controller instance.
     * @throws InternalErrorException if the reqeusted action is not supported by the current view related controller.
     */
    public function handleAction()
    {
        $controller = null;

        // handle view specific action
        switch ($this->viewId) {
            case self::$VIEW_LOGIN:
                $controller = new LoginViewController();
                break;
            case self::$VIEW_REGISTRATION:
                $controller = new RegistrationViewController();
                break;
            case self::$PARTIAL_VIEW_REGISTRATION_SUCCESS:
                $controller = new RegistrationViewController();
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelViewController();
                break;
            case self::$VIEW_MAIN:
                $controller = new MainViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNELS:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNEL:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNEL_CHAT:
                $controller = new ChannelViewController();
                break;
            default:
                throw new InternalErrorException("Unknown view with id: '" . $this->viewId . "' detected'");
        }

        return $controller->handleAction();
    }

    /**
     * Prepares the next intended view.
     *
     * @param string $nextView the id of the next intended view
     * @return array|null the arguments which will contain the template arguments
     * @throws InternalErrorException if the next intended view is not supported by teh current view related controller instance.
     */
    public function prepareView($nextView)
    {
        // render next view
        $controller = null;
        $args = array(
            "viewId" => $nextView
        );

        switch ($nextView) {
            case self::$VIEW_LOGIN:
                $controller = new LoginViewController();
                break;
            case self::$VIEW_REGISTRATION:
                $controller = new RegistrationViewController();
                break;
            case self::$PARTIAL_VIEW_REGISTRATION_SUCCESS:
                $controller = new RegistrationViewController();
                break;
            case self::$VIEW_START:
                $controller = new MainViewController();
                break;
            case self::$VIEW_MAIN:
                $controller = new MainViewController();
                break;
            case self::$PARTIAL_VIEW_NEW_CHANNEL:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNELS:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNEL:
                $controller = new ChannelViewController();
                break;
            case self::$PARTIAL_VIEW_CHANNEL_CHAT:
                $controller = new ChannelViewController();
                break;
            default:
                throw new InternalErrorException("Next view: '" . $nextView . "' cannot be handled by '" . __CLASS__ . "'");
        }

        // store the next intended view in the session
        if (!StringUtil::startWith($this->viewId, "partial")) {
            $this->sessionCtrl->setAttribute("formerView", $this->viewId);
        } else {
            $this->sessionCtrl->setAttribute("formerPartialView", $this->viewId);
        }
        // store the next intended view in the session
        if (!StringUtil::startWith($nextView, "partial")) {
            $this->sessionCtrl->setAttribute("currentView", $nextView);
        } else {
            $this->sessionCtrl->setAttribute("currentPartialView", $nextView);
        }

        // merges the current view related controller created arguments with the formerly prepared one
        $args = array_merge($args, $controller->prepareView($nextView));

        // Special case for start view, which provides no view output but sets http header
        if (StringUtil::compare($nextView, self::$VIEW_START)) {
            return null;
        }

        // return arguments which contains the template arguments
        return $args;
    }

    // #########################################################################
    // Private helper functions
    // #########################################################################
    /**
     * Gets the template renderer instance either from cache or creates a new one if no instance has been cached yet.
     *
     * @return TemplateController the template controller
     */
    private function getTemplateController()
    {
        $item = $this->pool->getItem("controller/view");
        if ($item->isMiss()) {
            $pool = PoolController::createFileSystemPool(TemplateController::$POOL_NAMESPACE, array("path" => ROOT_PATH . "/cache/templates"));
            $templateCtrl = new TemplateController($pool);
            $item->set($templateCtrl);
        }
        return $item->get();
    }

    /**
     * Answers the question if the to render template shall be cached or not.
     *
     * @param array $args the array to search for the proper parameter
     * @return bool null if no parameter is set, otherwise the set argument bool value
     */
    private function isToCacheTemplate(array $args)
    {
        if ((!isset($args)) || (!isset($args["cacheTemplate"]))) {
            return null;
        }
        return $args["cacheTemplate"];
    }

    /**
     * Answers the question if the to render template shall be recreated or not.
     *
     * @param array $args the array to search for the proper parameter
     * @return bool null if no parameter is set, otherwise the set argument bool value
     */
    private function isToRecreateTemplate(array $args)
    {
        if ((!isset($args)) || (!isset($args["recreateTemplate"]))) {
            return null;
        }
        return $args["recreateTemplate"];
    }
}