<?php

// set server root path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/semester-project');
require_once(ROOT_PATH . '/source/composer/vendor/autoload.php');
//require_once(ROOT_PATH . '/source/db/config/propel.php');

// log4php logging
$logger = Logger::getLogger("main");
$logger->info("This is an informational message.");
$logger->warn("I'm not feeling so good...");


// Web socket for php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;

$user = UserQuery::create()->find();
var_dump($user);

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