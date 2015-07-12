<?php
define('ROOT_PATH', 'C:/Users/Thomas/Git Repositories/Github/FH-Hagenberg/PHP/PHP/semester-project-2015');
require_once(ROOT_PATH . '/source/composer/vendor/autoload.php');
require_once(ROOT_PATH . '/source/demo/websocket/chat.php');
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    4999
);

$server->run();