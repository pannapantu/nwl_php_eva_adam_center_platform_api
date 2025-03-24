<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
    
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
    exit(0);
}

define("BASEPATH", true);

use AltoRouter as Router;

require_once realpath(__DIR__. "/vendor/autoload.php");

$router = new Router();

$router->map('GET', '/', function () {
    require __DIR__ . '/view/landing.php';
});

$router->map('POST', '/api/v1/login', function () {
    require __DIR__ . '/controllers/~auth/login.php';
});

$router->map('POST', '/api/v1/register', function () {
    require __DIR__ . '/controllers/~auth/register.php';
});

$router->map('GET', '/api/v1/user', function(){
    require __DIR__.'/controllers/~user/get_all.php';
});

$router->map('GET', '/api/v1/user/[i:id]', function ($id) {
    require __DIR__ . '/controllers/~user/get_by_id.php';
});

$router->map('POST', '/api/v1/user/create', function () {
    require __DIR__ . '/controllers/~user/create.php';
});

$router->map('POST', '/api/v1/user/update/[i:id]', function ($id) {
    require __DIR__ . '/controllers/~user/update.php';
});

$router->map('POST', '/api/v1/user/delete/[i:id]', function ($id) {
    require __DIR__ . '/controllers/~user/delete.php';
});

$router->map('GET', '/api/v1/message/getAllSignSize', function(){
    require __DIR__.'/controllers/message/getAllSignSize.php';
    
});

$router->map('GET', '/api/v1/message/getAllFormatMessage', function(){
    require __DIR__.'/controllers/message/getAllFormatMessage.php';
});

$router->map('POST', '/api/v1/message/deleteMessageById/[*:XVMsgCode]', function ($XVMsgCode) {
    require __DIR__.'/controllers/message/deleteMessageById.php';
});

$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}