<?php

// set server root path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . 'semester-project');
require_once(ROOT_PATH . '/source/composer/vendor/autoload.php');
require_once(ROOT_PATH . '/source/common/Objects.php');
require_once(ROOT_PATH . '/source/common/ObjectUtil.php');
require_once(ROOT_PATH . '/source/common/Exceptions.php');
require_once(ROOT_PATH . '/source/view/controller/SecurityController.php');
require_once(ROOT_PATH . '/source/view/controller/SessionController.php');
//require_once(ROOT_PATH . '/source/db/config/propel.php');

use \SCM4\View\Controller\SecurityController;

// log4php logging
$logger = Logger::getLogger("main");
$logger->info("This is an informational message.");
$sessionController = \SCM4\View\Controller\SessionController::getInstance();
$sessionController->startSession();

var_dump($sessionController->getAttribute(\SCM4\View\Controller\SessionController::$SESSION_START));
$security = SecurityController::getInstance();
var_dump($security);
$security->logoutUser(-666);
var_dump($_SESSION);
echo "</br></br></br>";
$logger->warn("I'm not feeling so good..." . $sessionController . PHP_EOL);


// Web socket for php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;

$user = UserQuery::create()->find();
//var_dump($user);

Twig_Autoloader::register();

// TODO: Need to deploy internal and private resources to dir where they aren't accessible.
$loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . 'semester-project/source/view/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => $_SERVER['DOCUMENT_ROOT'] . 'semester-project/cache/templates',
));

// TODO: Cache implementations for templates, localizations and any other data
// Create Driver with default options
$driver = new Stash\Driver\FileSystem();
$driver->setOptions(array());

// Inject the driver into a new Pool object.
$pool = new Stash\Pool($driver);

// New Items will get and store their data using the same Driver.
$item = $pool->getItem('path/to/data');

var_dump($item);


class Chat implements MessageComponentInterface
{
    private $logger;

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $logger = Logger::getLogger("Chat");
        $logger->info("new connection received");
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $logger = Logger::getLogger("Chat");
        $logger->info("ncon closed");
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $logger = Logger::getLogger("Chat");
        $logger->info("error ");
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $logger = Logger::getLogger("Chat");
        $logger->info("message received");
    }

}