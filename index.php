<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$config['db']['host']   = 'localhost';
$config['db']['user']   = 'user';
$config['db']['pass']   = 'password';
$config['db']['dbname'] = 'exampleapp';
//figure out DB details here


$app = new \Slim\App(['settings' => $config]);
$container = $app-> getContainer();

//build logging into other apps
$container['logger'] = function($c) {
	$logger = new \Monolog\Logger('my_logger');
	$file_handler = new\Monolog\Handler\StreamHandler('../logs/app.log');
	$logger->pushHandler($file_handler);
	return $logger;
};

//look at PDO settings and create simple CRUD 
$container['db'] = function($c) {
	$db = $c['settings']['db'];
	$pdo = new PDO('mysql:host='.$db['host'] .';dbname='.$db['dbname'],$db['user'], $db['pass']);	
};


//ammend below to accept ID number in URI and then display name of item from DB
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});


$app->run();


?>