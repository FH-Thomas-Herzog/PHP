<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/16/2015
 * Time: 9:33 PM
 */

namespace source\view\controller;

use source\common\BaseObject;
use source\common\InternalErrorException;

/**
 * This class represents the view controller which handles the creation of the available views.
 * Class ViewController
 * @package SCM4\View\Controller
 */
class ViewController extends BaseObject
{

    public static $VIEW_LOGIN = "login";

    public static $VIEW_LOGOUT = "logout";

    public static $VIEW_START = "start";

    public static $REGISTRATION = "register";

    public static $CONTEXT_ROOT = "/php-semester-project";

    private static $POOL_NAME = "/view/controller/ViewController";

    private static $VIEW_CONTROLLER_POOL = "VIEW_CONTROLLER_POOL";

    private static $CSS_ROOT = "/public/css";

    private $twig;

    /**
     * Constructs this View handler and inits the used template engine.
     * Be aware that the parameters must be given if no twig environment can be retrieved from the cache.
     *
     * @param null $templateLocation the location of the templates.
     * @param array|null $twigOptions the options for the twig environment
     */
    public function __construct($templateLocation = null, array $twigOptions = null)
    {
        parent::__construct();
        $sessionCtrl = SessionController::getInstance();
        $pool = $sessionCtrl->getAttribute(self::$VIEW_CONTROLLER_POOL);
        if ($pool == null) {    // TODO: Setup twig correctly
            $loader = new \Twig_Loader_Filesystem($templateLocation);
            $this->twig = new \Twig_Environment($loader, $twigOptions);
        }
    }

    /**
     * Gets the view content of the given viewId.
     * @param string $viewId the view id to render
     * @param array $args the arguments for the template engine
     * @throws InternalErrorException if the view id does not map to a valid view.
     */
    public function renderView($viewId, array $args)
    {
        $args["cssRoot"] = self::$CONTEXT_ROOT . self::$CSS_ROOT;
        return $this->twig->loadTemplate($viewId . ".html")->render($args);
    }
}