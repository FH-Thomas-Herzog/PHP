<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/16/2015
 * Time: 9:33 PM
 */

namespace source\view\controller;

use \source\common\BaseObject;
use \source\common\InternalErrorException;
use \Stash\Pool;

/**
 * This class represents the template controller which renders the templates and returns the rendered result.
 * This class supports Stash caching for rendered templates
 * @package SCM4\View\Controller
 */
class TemplateController extends BaseObject
{

    public static $CONTEXT_ROOT = "/php-semester-project";


    public static $POOL_NAMESPACE = "ViewController";

    private static $CSS_ROOT = "/public/css";

    private static $JS_ROOT = "/public/js";

    private static $TWIG_OPTIONS = array(
        'cache' => ROOT_PATH . '/cache/templates/compiled',
        'debug' => true,
        'auto_reload ' => true, // autoload of templates for development puspose only
        'strict_variables' => false, // avoid undefined variables
        'autoescape' => true // escape html
    );

    private $twig;

    private $pool;

    /**
     * Constructs this View handler and inits the used template engine.
     * Be aware that the parameters must be given if no twig environment can be retrieved from the cache.
     *
     * @param \Stash\Pool pool the stash pool to use by this controller
     * @throws InternalErrorException if the pool is not set
     */
    public function __construct(Pool $pool)
    {
        parent::__construct();
        if (!isset($pool)) {
            throw new InternalErrorException("Pool not set but needed");
        }

        $this->pool = $pool;
        $item = $this->pool->getItem("/twig/environment");
        // Init twig environment if not already present in cache
        if ($item->isMiss()) {
            $loader = new \Twig_Loader_Filesystem(ROOT_PATH . "/source/view/templates");
            $this->twig = new \Twig_Environment($loader, self::$TWIG_OPTIONS);
            $item->set($this->twig);
        } else {
            $this->twig = $item->get();
        }
    }

    /**
     * Gets the view content of the given viewId.
     *
     * @param string $viewId the view id to render
     * @param boolean|false cache true if the rendered view shall be cached and retrieved from cache
     * @param boolean|false recreate true if an already cached item shall be overwritten. Works only tih $cache = true
     * @param array $args the arguments for the template engine
     * @return the rendered view
     * @throws InternalErrorException if the view id does not map to a valid view.
     */
    public function renderView($viewId, $cache = false, $recreate = false, array $args = array())
    {
        if (!isset($args)) {
            $args = array();
        }

        $rendered = null;
        if ($cache) {
            $item = $this->pool->getItem("/twig/rendered/" . $viewId);
            if ($recreate || $item->isMiss()) {
                $rendered = $this->twig->loadTemplate($viewId . ".html")->render(self::addCommonArguments($viewId, $args));
                $item->set($rendered);
            } else {
                $rendered = $item->get();
            }
        } else {
            $rendered = $this->twig->loadTemplate($viewId . ".html")->render(self::addCommonArguments($viewId, $args));
        }
        return $rendered;
    }

    /**
     * Adds the common arguments which should be present in each template.
     *
     * @param $viewId the view id as hidden field in forms
     * @param array $args the argument array to add arguments too
     * @return array the modified argument array
     */
    private static function addCommonArguments($viewId, array $args)
    {
        if (isset($args)) {
            $args["cssRoot"] = self::$CONTEXT_ROOT . self::$CSS_ROOT;
            $args["jsRoot"] = self::$CONTEXT_ROOT . self::$JS_ROOT;
            $args[ViewController::$VIEW_ID] = (string)$viewId;
        }
        return $args;
    }
}