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
use Stash\Pool;

/**
 * This class represents the template controller which renders the templates and returns the rendered result.
 * This class supports Stash caching for rendered templates.
 *
 * @package SCM4\View\Controller
 */
class TemplateController extends BaseObject
{
    public static $POOL_NAMESPACE = "ViewController";

    /**
     * The static stash options used for configuring the Stash cache pool
     * @var array
     */
    private static $TWIG_OPTIONS = array(
        // Seems that compiled template is not considered by engine.
        // There is not time for further investigation on this, therefore no compiled cache activated
        //'cache' => ROOT_PATH . '/cache/templates/compiled',
        'debug' => false,
        'auto_reload ' => true,
        'strict_variables' => false,
        'autoescape' => true
    );

    private $twig;

    private $pool;

    /**
     * Constructs this template controller instance and initializes it with the given stash pool.
     * Delegates to the base class so that common initialization work can be done.
     *
     * @param Pool $pool the stash cache pool to use
     * @throws InternalErrorException if no pool is given
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
     * Renders the template defined by the given view id
     *
     * @param string $viewId the id of the template to render
     * @param boolean|false cache true if the rendered view shall be cached and retrieved from cache
     * @param boolean|false recreate true if an already cached item shall be overwritten. Works only with $cache = true
     * @param array $args the arguments for the template engine
     * @return the rendered view
     * @throws InternalErrorException if the viewId does not map to a valid template.
     */
    public function renderView($viewId, $cache = false, $recreate = false, $args = null)
    {
        if (!isset($args)) {
            $args = array();
        }

        $rendered = null;
        try {
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
        } catch (\Exception $e) {
            throw new InternalErrorException("Template for view with id: '" . $viewId . "' not found." . PHP_EOL . $e->getMessage());
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
            $args["viewId"] = (string)$viewId;
        }
        return $args;
    }
}