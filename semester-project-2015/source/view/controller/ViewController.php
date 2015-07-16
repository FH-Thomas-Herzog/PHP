<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 7/16/2015
 * Time: 9:33 PM
 */

namespace SCM4\View\Controller;

use SCM4\Common\BaseObject;
use SCM4\Common\Exception\InternalErrorException;

/**
 * This class represents the view controller which handles the creation of the available views.
 * Class ViewController
 * @package SCM4\View\Controller
 */
class ViewController extends BaseObject
{

    public static $LOGIN = "login";

    public static $LOGOUT = "logout";

    public static $REGISTRATION = "register";

    private $engine;

    /**
     * Constructs this View handler and inits the used template engine
     */
    public function __construct()
    {
        parent::__construct();
        // TODO: Setup twig correctly
        $loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . 'semester-project/source/view/templates');
        $engine = new Twig_Environment($loader, array(
            'cache' => $_SERVER['DOCUMENT_ROOT'] . 'semester-project/cache/templates',
        ));
        // TODO: Setup stash for caching the twig engine and maybe the rendered templates (login for instance)
    }

    /**
     * Gets the view content of the given viewId.
     * @param string $viewId the view id to render
     * @param array $args the arguments for the template engine
     * @throws InternalErrorException if the view id does not map to a valid view.
     */
    public function renderView($viewId, array $args)
    {
        // TODO: Validate view id and render view here
    }
}